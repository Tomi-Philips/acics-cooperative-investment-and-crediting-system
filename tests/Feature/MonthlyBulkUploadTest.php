<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\MonthlyUpload;
use App\Models\ShareTransaction;
use App\Models\SavingTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MonthlyBulkUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com'
        ]);
        
        // Create test members with initial balances
        $this->member1 = User::factory()->create(['email' => 'member1@test.com']);
        $this->member1->member()->create([
            'member_number' => 'COOP001',
            'total_share_amount' => 1000.00,
            'total_saving_amount' => 500.00,
            'status' => 'active'
        ]);
        
        $this->member2 = User::factory()->create(['email' => 'member2@test.com']);
        $this->member2->member()->create([
            'member_number' => 'COOP002',
            'total_share_amount' => 2000.00,
            'total_saving_amount' => 1000.00,
            'status' => 'active'
        ]);
    }

    /** @test */
    public function it_prevents_duplicate_monthly_uploads()
    {
        // Create existing monthly upload for June 2024
        MonthlyUpload::create([
            'year' => 2024,
            'month' => 6,
            'upload_type' => 'financial_records',
            'file_name' => 'june_2024.xlsx',
            'total_records' => 10,
            'processed_records' => 10,
            'failed_records' => 0,
            'update_fields' => ['shares', 'savings'],
            'description' => 'June 2024 Contributions',
            'status' => 'completed',
            'uploaded_by' => $this->admin->id,
        ]);

        // Try to upload for the same month
        $file = $this->createTestExcelFile();
        
        $response = $this->actingAs($this->admin)->post(route('admin.bulk_updates.upload'), [
            'excel_file' => $file,
            'transaction_date' => '2024-06-15',
            'description' => 'June 2024 Additional Contributions',
            'update_fields' => ['shares', 'savings'],
            'missing_data' => 'skip',
            'upload_type' => 'monthly_contributions'
        ]);

        $response->assertRedirect()
                ->assertSessionHas('error');
        
        $this->assertStringContainsString('June 2024', session('error'));
    }

    /** @test */
    public function it_handles_monthly_contributions_correctly()
    {
        $file = $this->createTestExcelFile();
        
        // Initial balances
        $initialShareBalance1 = $this->member1->member->total_share_amount;
        $initialSavingBalance1 = $this->member1->member->total_saving_amount;
        
        // Upload monthly contributions
        $response = $this->actingAs($this->admin)->post(route('admin.bulk_updates.upload'), [
            'excel_file' => $file,
            'transaction_date' => '2024-06-15',
            'description' => 'June 2024 Monthly Contributions',
            'update_fields' => ['shares', 'savings'],
            'missing_data' => 'skip',
            'upload_type' => 'monthly_contributions'
        ]);

        $response->assertSuccessful();
        
        // Process the upload
        $sessionId = session('bulk_update_session_id');
        $processResponse = $this->actingAs($this->admin)->post(route('admin.bulk_updates.process'), [
            'session_id' => $sessionId
        ]);

        // Verify balances were incremented (not replaced)
        $this->member1->member->refresh();
        $this->assertEquals($initialShareBalance1 + 100, $this->member1->member->total_share_amount);
        $this->assertEquals($initialSavingBalance1 + 50, $this->member1->member->total_saving_amount);
        
        // Verify transactions were created
        $this->assertDatabaseHas('share_transactions', [
            'user_id' => $this->member1->id,
            'amount' => 100,
            'type' => 'credit'
        ]);
        
        $this->assertDatabaseHas('saving_transactions', [
            'user_id' => $this->member1->id,
            'amount' => 50,
            'type' => 'credit'
        ]);
        
        // Verify monthly upload record was created
        $this->assertDatabaseHas('monthly_uploads', [
            'year' => 2024,
            'month' => 6,
            'status' => 'completed',
            'uploaded_by' => $this->admin->id
        ]);
    }

    /** @test */
    public function it_handles_cumulative_balances_correctly()
    {
        $file = $this->createTestExcelFile();
        
        // Upload cumulative balances
        $response = $this->actingAs($this->admin)->post(route('admin.bulk_updates.upload'), [
            'excel_file' => $file,
            'transaction_date' => '2024-06-15',
            'description' => 'June 2024 Balance Reconciliation',
            'update_fields' => ['shares', 'savings'],
            'missing_data' => 'skip',
            'upload_type' => 'cumulative_balances'
        ]);

        $response->assertSuccessful();
        
        // Process the upload
        $sessionId = session('bulk_update_session_id');
        $processResponse = $this->actingAs($this->admin)->post(route('admin.bulk_updates.process'), [
            'session_id' => $sessionId
        ]);

        // Verify balances were set to exact amounts (not incremented)
        $this->member1->member->refresh();
        $this->assertEquals(100, $this->member1->member->total_share_amount); // Set to file amount
        $this->assertEquals(50, $this->member1->member->total_saving_amount);  // Set to file amount
    }

    private function createTestExcelFile()
    {
        Storage::fake('local');
        
        // Create a simple CSV content for testing
        $csvContent = "SNO,COOPNO,SURNAME,OTHERNAMES,SHARES,SAVINGS,LOAN_REPAY,LOAN_INT,ESSENTIAL,NON_ESSENTIAL,ELECTRONICS,TOTAL\n";
        $csvContent .= "1,COOP001,Test,Member1,100,50,0,0,0,0,0,150\n";
        $csvContent .= "2,COOP002,Test,Member2,200,100,0,0,0,0,0,300\n";
        
        return UploadedFile::fake()->createWithContent('test_upload.csv', $csvContent);
    }
}
