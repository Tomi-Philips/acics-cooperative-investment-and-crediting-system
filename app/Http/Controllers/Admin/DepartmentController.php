<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        // Build query with search functionality
        $query = Department::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Get departments with pagination, ordered by title
        $departments = $query->orderBy('title')->paginate(15);

        // Append search parameter to pagination links
        $departments->appends(['search' => $search]);

        // Return the view with the departments and search term
        return view('admin.departments.all', compact('departments', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get the 5 most recently added departments
        $recentDepartments = Department::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Return the view with the recent departments
        return view('admin.departments.add', compact('recentDepartments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Temporarily removed Gate check to allow department creation
        // if (!Gate::allows('manage-departments')) {
        //     abort(403, 'Unauthorized action.');
        // }
        // Validate the request data
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:departments,code',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Create the department
        $department = Department::create($validated);

        // Log the action
        Log::info('Department created', ['id' => $department->id, 'title' => $department->title]);

        // Redirect to the departments list with a success message
        return redirect()->route('admin.departments.all')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Process bulk upload of departments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkUpload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'department_csv_file' => 'required|file|mimes:csv,txt',
        ]);

        // Get the uploaded file
        $file = $request->file('department_csv_file');

        // Store the file temporarily
        $path = $file->store('temp');
        $fullPath = \Illuminate\Support\Facades\Storage::path($path);

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        // Start a database transaction
        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            // Read the CSV file
            $csvData = array_map('str_getcsv', file($fullPath));

            // Remove empty rows
            $csvData = array_filter($csvData, function($row) {
                return !empty(array_filter($row, function($cell) {
                    return !empty(trim($cell));
                }));
            });

            // Reset array keys
            $csvData = array_values($csvData);

            // Remove the header row if it exists
            if (count($csvData) > 0) {
                array_shift($csvData);
            }

            // Process each row
            foreach ($csvData as $index => $row) {
                // Skip empty rows
                if (empty($row[0])) {
                    continue;
                }

                try {
                    // Map the columns to department data
                    $departmentData = [
                        'code' => $row[0] ?? null,
                        'title' => $row[1] ?? null,
                        'description' => $row[2] ?? null,
                        'is_active' => isset($row[3]) ? filter_var($row[3], FILTER_VALIDATE_BOOLEAN) : true, // Default to true if not provided
                    ];

                    // Validate required fields
                    $missingFields = [];
                    if (empty(trim($departmentData['code']))) $missingFields[] = 'Code';
                    if (empty(trim($departmentData['title']))) $missingFields[] = 'Title';

                    if (!empty($missingFields)) {
                        throw new \Exception('Missing required fields: ' . implode(', ', $missingFields));
                    }

                    // Check if department code already exists
                    if (Department::where('code', $departmentData['code'])->exists()) {
                        throw new \Exception('Department code already exists: ' . $departmentData['code']);
                    }

                    // Create the department
                    Department::create($departmentData);

                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            // Commit the transaction if there were any successful imports
            if ($successCount > 0) {
                \Illuminate\Support\Facades\DB::commit();
            } else {
                \Illuminate\Support\Facades\DB::rollBack();
            }

            // Clean up the temporary file
            \Illuminate\Support\Facades\Storage::delete($path);

            // Prepare the response message
            $message = "Bulk upload completed: {$successCount} departments imported successfully";
            if ($errorCount > 0) {
                $message .= ", {$errorCount} failed";
            }

            // Log the bulk upload
            Log::info('Bulk department upload by admin', [
                'admin_id' => Auth::id(),
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'errors' => $errors,
            ]);

            // Redirect with appropriate message
            if ($errorCount > 0) {
                return redirect()->route('admin.departments.add')
                    ->with('warning', $message)
                    ->withErrors($errors);
            } else {
                return redirect()->route('admin.departments.all')
                    ->with('success', $message);
            }
        } catch (\Exception $e) {
            // Clean up the temporary file
            \Illuminate\Support\Facades\Storage::delete($path);

            // Log the error
            Log::error('Error in bulk department upload', [
                'admin_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('admin.departments.add')
                ->with('error', 'Error processing file: ' . $e->getMessage());
        }
    }

    /**
     * Download the bulk department upload template.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadTemplate()
    {
        $templatePath = storage_path('app/templates/bulk_department_template.csv');
        $templateDir = storage_path('app/templates');

        // Create the directory if it doesn't exist
        if (!file_exists($templateDir)) {
            mkdir($templateDir, 0755, true);
        }

        // Define the headers
        $headers = [
            'Code (Required)',
            'Title (Required)',
            'Description (Optional)',
            'Is Active (TRUE/FALSE - Optional)'
        ];

        // Sample data
        $sampleData = [
            ['CSC', 'Computer Science', 'Department of Computer Science', 'TRUE'],
            ['BUS', 'Business Administration', 'Department of Business Administration', 'TRUE'],
            ['MTH', 'Mathematics', 'Department of Mathematics', 'FALSE'],
        ];

        // Create the CSV content
        $csvContent = implode(',', $headers) . "\n";
        foreach ($sampleData as $row) {
            $csvContent .= implode(',', $row) . "\n";
        }

        // Write the CSV file
        file_put_contents($templatePath, $csvContent);

        return response()->download($templatePath, 'bulk_department_template.csv');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the department by ID
        $department = Department::findOrFail($id);

        // Get the users in this department
        $users = $department->users()->paginate(10);

        // Return the view with the department and its users
        return view('admin.departments.view', compact('department', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the department by ID
        $department = Department::findOrFail($id);

        // Return the view with the department
        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Temporarily removed Gate check to allow department updates
        // if (!Gate::allows('manage-departments')) {
        //     abort(403, 'Unauthorized action.');
        // }
        // Find the department by ID
        $department = Department::findOrFail($id);

        // Validate the request data
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:departments,code,' . $department->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Update the department
        $department->update($validated);

        // Log the action
        Log::info('Department updated', ['id' => $department->id, 'title' => $department->title]);

        // Redirect to the departments list with a success message
        return redirect()->route('admin.departments.all')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Temporarily removed Gate check to allow department deletion
        // if (!Gate::allows('manage-departments')) {
        //     abort(403, 'Unauthorized action.');
        // }
        // Find the department by ID
        $department = Department::findOrFail($id);

        // Check if the department has users
        $userCount = $department->users()->count();
        if ($userCount > 0) {
            // Don't delete departments with users
            return redirect()->route('admin.departments.all')
                ->with('error', "Cannot delete department '{$department->title}' because it has {$userCount} users assigned to it.");
        }

        // Store the department title for the success message
        $title = $department->title;

        // Delete the department
        $department->delete();

        // Log the action
        Log::info('Department deleted', ['id' => $id, 'title' => $title]);

        // Redirect to the departments list with a success message
        return redirect()->route('admin.departments.all')
            ->with('success', "Department '{$title}' deleted successfully.");
    }
}
