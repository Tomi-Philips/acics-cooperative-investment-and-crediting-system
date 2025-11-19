<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (!Schema::hasColumn('members', 'phone')) {
                $table->string('phone')->nullable()->after('member_number');
            }

            if (!Schema::hasColumn('members', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('members', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('address');
            }

            if (!Schema::hasColumn('members', 'gender')) {
                $table->string('gender')->nullable()->after('date_of_birth');
            }

            if (!Schema::hasColumn('members', 'position')) {
                $table->string('position')->nullable()->after('gender');
            }

            if (!Schema::hasColumn('members', 'next_of_kin_name')) {
                $table->string('next_of_kin_name')->nullable()->after('position');
            }

            if (!Schema::hasColumn('members', 'next_of_kin_relationship')) {
                $table->string('next_of_kin_relationship')->nullable()->after('next_of_kin_name');
            }

            if (!Schema::hasColumn('members', 'next_of_kin_phone')) {
                $table->string('next_of_kin_phone')->nullable()->after('next_of_kin_relationship');
            }

            if (!Schema::hasColumn('members', 'next_of_kin_address')) {
                $table->text('next_of_kin_address')->nullable()->after('next_of_kin_phone');
            }

            if (!Schema::hasColumn('members', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('next_of_kin_address');
            }

            if (!Schema::hasColumn('members', 'id_document')) {
                $table->string('id_document')->nullable()->after('profile_photo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'address',
                'date_of_birth',
                'gender',
                'position',
                'next_of_kin_name',
                'next_of_kin_relationship',
                'next_of_kin_phone',
                'next_of_kin_address',
                'profile_photo',
                'id_document',
            ]);
        });
    }
};
