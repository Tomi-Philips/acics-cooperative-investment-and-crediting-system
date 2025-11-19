# ACICS Comprehensive User Navigation Guide

## Table of Contents
1. [Introduction](#introduction)
2. [System Architecture](#system-architecture)
3. [Core Business Logic](#core-business-logic)
4. [Getting Started](#getting-started)
5. [User Interface Layout](#user-interface-layout)
6. [Authentication Pages](#authentication-pages)
7. [User Dashboard](#user-dashboard)
8. [Loan Management System](#loan-management-system)
9. [Commodity Trading System](#commodity-trading-system)
10. [Financial Management](#financial-management)
11. [Account Management](#account-management)
12. [Support System](#support-system)
13. [Administrative Panel](#administrative-panel)
14. [System Architecture and Data Flow](#system-architecture-and-data-flow)
15. [Public Pages](#public-pages)
16. [Error Pages](#error-pages)
17. [Mobile Responsiveness](#mobile-responsiveness)
18. [Keyboard Navigation](#keyboard-navigation)
19. [Accessibility Features](#accessibility-features)
20. [Troubleshooting](#troubleshooting)

## Introduction

ACICS (Academic Cooperative Investment and Credit Society) is a comprehensive web-based cooperative society management system designed to streamline financial operations for cooperative organizations. This detailed guide covers every page, feature, and navigation path within the ACICS software.

### System Architecture
- **Frontend**: Laravel Blade templates with Tailwind CSS
- **Backend**: Laravel PHP framework
- **Database**: MySQL with transaction-based financial calculations
- **Authentication**: Multi-role system (User, Admin)
- **Real-time Features**: Dashboard updates, notifications, transaction processing

### Core Business Logic

#### Financial Calculation Methodology
ACICS uses a transaction-based financial calculation system that ensures data integrity and audit trails. All financial balances are calculated dynamically from transaction records rather than stored values.

**Key Calculation Rules:**
- **Loan Eligibility**: 2 × (Savings + Shares - Active Loans - Commodity Balances - Electronics Balance)
- **Interest Rate**: Fixed 10% on all loan principal amounts
- **Repayment Method**: Bursary deduction only (no fixed terms)
- **Entrance Fee**: ₦1,000 one-time payment required for loan eligibility
- **Membership Requirement**: Minimum 6 months membership for loan applications

#### Transaction Types
- **Savings Transactions**: Credit/debit operations on member savings accounts
- **Share Transactions**: Investment contributions with maximum ₦10,000 per contribution
- **Loan Transactions**: Principal disbursements and repayments
- **Commodity Transactions**: Essential and non-essential goods purchases
- **Electronics Transactions**: Electronic equipment purchases
- **Entrance Fee**: One-time membership fee

### Getting Started
1. **Access URL**: Navigate to the ACICS application URL
2. **Browser Requirements**: Modern browsers (Chrome 90+, Firefox 88+, Safari 14+, Edge 90+)
3. **Network**: Stable internet connection required
4. **Device Support**: Desktop, tablet, and mobile responsive design

## User Interface Layout

### Global Navigation Elements

#### Top Header Bar
- **Logo**: Click to return to dashboard (authenticated users) or homepage (public)
- **Notifications Bell**: Click to view recent notifications
  - Red badge shows unread count
  - Dropdown displays recent notifications
  - "Mark all as read" option
  - "View all notifications" link
- **User Profile Dropdown**: Click to access account options
  - Profile picture/initials avatar
  - "Your Profile" link
  - "Settings" link
  - "Sign out" button

#### Left Sidebar Navigation (User Panel)
Located at `resources/views/layouts/user.blade.php`, contains:
- **Dashboard**: Main overview page
- **Loans Dropdown**:
  - Loan Overview (active loans table)
  - Loan Application (new loan request form)
- **Commodity**: Marketplace for goods and services
- **Request Saving Withdrawal**: Savings withdrawal form
- **Support Tickets**: Help desk system
- **Transaction Report**: Financial history and exports
- **Settings**: Account preferences
- **My Profile**: Personal information management
- **Logout**: Secure sign out

#### Mobile Navigation
- **Hamburger Menu**: Three-line icon (☰) in top-left
- **Responsive Sidebar**: Collapses on small screens
- **Touch-Friendly**: Large buttons and touch targets
- **Swipe Gestures**: Supported on mobile devices

## Authentication Pages

### Public Homepage (`resources/views/welcome.blade.php`)
**URL**: `/`
**Features**:
- Hero section with ACICS introduction
- Login and registration call-to-action buttons
- Business rules overview
- Contact information
- Footer with links to terms, privacy, FAQ

### Login Page (`resources/views/auth/login.blade.php`)
**URL**: `/login`
**Navigation Steps**:
1. Enter email address
2. Enter password
3. Click "Login" button
4. Optional: "Remember me" checkbox
5. "Forgot Password?" link redirects to password reset

**Features**:
- Email/password authentication
- "Remember me" functionality
- Password reset link
- Registration link for new users
- Error messages for invalid credentials

### Registration Page (`resources/views/auth/membership_registration.blade.php`)
**URL**: `/register`
**Form Fields**:
- Full Name (required)
- Email Address (required)
- Phone Number (required)
- Department selection (dropdown)
- Staff ID (optional)
- Password (required, with confirmation)
- Terms acceptance checkbox

**Navigation Flow**:
1. Fill all required fields
2. Accept terms and conditions
3. Click "Register" button
4. Email verification sent
5. Redirect to application status page

### Password Reset (`resources/views/auth/reset.blade.php`)
**URL**: `/password/reset`
**Process**:
1. Enter registered email
2. Click "Send Password Reset Link"
3. Check email for reset instructions
4. Click email link to reset form
5. Enter new password
6. Confirm password reset

### Application Status Check (`resources/views/auth/application_status_check.blade.php`)
**URL**: `/application-status`
**Features**:
- Reference number input
- Status lookup functionality
- Real-time status display
- Contact information for support

## User Dashboard (`resources/views/user/index.blade.php`)

### Main Dashboard Layout
**URL**: `/user/dashboard` (post-login redirect)
**Key Sections**:

#### Header Section
- Welcome message with user name
- Member ID display
- Join date information
- "View Profile" button (top-right)

#### Entrance Fee Status Banner
- Green banner: "Entrance fee status: Paid"
- Amber banner: "Entrance fee status: Not paid"

#### Financial Balance Cards (Grid Layout)
Interactive cards showing real-time calculated balances:
- **Savings Balance**: ₦X,XXX.XX with "Available funds" subtitle
  - Calculated from: `saving_transactions` (credits - debits)
- **Shares Balance**: ₦X,XXX.XX with "Investment value" subtitle
  - Calculated from: `share_transactions` (credits - debits)
- **Loan Balance**: ₦X,XXX.XX with "Outstanding amount" subtitle
  - Calculated from: Active loan principal minus principal payments made
- **Loan Interest**: ₦X,XXX.XX with "Total interest owed" subtitle
  - Calculated as: 10% of original loan amount minus interest payments made
- **Essential Commodity**: ₦X,XXX.XX with "Purchase credit" subtitle
  - Balance from `user_commodities` table where type = 'essential'
- **Non-Essential Commodity**: ₦X,XXX.XX with "Purchase credit" subtitle
  - Balance from `user_commodities` table where type = 'non_essential'
- **Electronics**: ₦X,XXX.XX with "Purchase credit" subtitle
  - Calculated from `electronics` table transactions (credits - debits)

#### Additional Stats Cards
- **Active Loans**: Count display with "Currently active" subtitle
- **Commodity Items**: Count with "Available for purchase" subtitle
- **Support Tickets**: Count with "Pending responses" subtitle

#### Recent Transactions Table
**Columns**:
- Date & Time (formatted as "M d, Y" and "H:i:s")
- Type (color-coded badges: purple for shares, green for savings, etc.)
- Amount (₦X,XXX.XX)
- Charges (-₦XX.XX)
- Net Amount (+/-₦X,XXX.XX)
- Status (Completed/Pending/Failed badges)

**Transaction Sources**:
- Share transactions from `share_transactions` table
- Saving transactions from `saving_transactions` table
- Loan payments from `loan_payments` table
- Regular transactions from `transactions` table
- Commodity transactions from `commodity_transactions` table
- Electronics transactions from `electronics` table
- Loan disbursements from `loans` table

**Features**:
- Pagination controls (Previous/Next buttons)
- "View All" button links to transaction report
- Hover effects on table rows
- Responsive design for mobile

## Loan Management System

### Loan Overview Page (`resources/views/user/loan_board.blade.php`)
**URL**: `/user/loan_board`
**Sidebar Access**: Loans → Loan Overview

#### Page Header
- "Loan Overview" title
- "Manage your active loans and repayment schedule" subtitle
- "Apply for New Loan" button (conditional on eligibility)

#### Eligibility Information Box
- Member requirements (6 months membership)
- Available loan amount display
- Current active loan balance
- Interest rate (10%)
- Repayment method (Bursary Deduction)
- Multiple loans allowed notice

#### Active Loans Display
**For users with active loans**:
- Loan cards grid showing:
  - Loan number (LOAN-XXXXX format)
  - Original amount
  - Remaining balance
  - Interest amount (10% of original)
- Total active loan balance summary

#### Loans Table
**Columns**:
- Loan ID (LOAN-XXXXX)
- Amount (₦X,XXX.XX)
- Interest Rate (XX%)
- Status (Active/Pending/Approved/Rejected/Completed)
- Next Payment (date or status message)
- Action (View Details button)

**Status Indicators**:
- Green: Active
- Yellow: Pending Approval
- Blue: Approved
- Red: Rejected
- Gray: Completed

### Loan Application Page (`resources/views/user/loan_application.blade.php`)
**URL**: `/user/loan_application`
**Sidebar Access**: Loans → Loan Application

#### Eligibility Check Display
- Available loan amount (calculated as 2×(Savings+Shares-Loans-Commodities))
- Current financial summary cards
- Ineligibility warnings (if applicable)

#### Application Form
**Required Fields**:
- Loan Amount (₦1,000 minimum, eligible amount maximum)
- Purpose (textarea, required)
- Additional Information (optional textarea)

**Form Features**:
- Real-time calculation preview
- Amount validation (cannot exceed available balance)
- Terms acceptance checkbox
- "Submit Loan Application" button

#### Important Notes Section
- Physical form requirement notice
- 10% fixed interest rate
- Bursary deduction repayment method

#### Loan Calculation Logic
**Maximum Loan Amount Formula**:
```
Max Loan = 2 × (Savings + Shares - Active Loans - Essential Commodities - Non-Essential Commodities - Electronics)
```

**Interest Calculation**:
```
Interest Amount = Loan Amount × 10%
Total Repayment = Loan Amount + Interest Amount
```

**Eligibility Requirements**:
- Minimum 6 months membership
- Entrance fee paid (₦1,000)
- Positive net assets after liabilities
- No fixed repayment terms (flexible bursary deductions)

### Loan Details Page (`resources/views/user/loan_details.blade.php`)
**URL**: `/user/loan_details/{id}`
**Access**: Click "View Details" from loan overview

#### Loan Summary Cards
- Loan Amount (original principal)
- Interest Rate (percentage)
- Total Payment (principal + interest)
- Remaining Balance
- Interest Paid
- Next Payment Due

#### Payment Schedule Table
**Columns**:
- Payment Date
- Amount Due
- Status (Paid/Pending/Overdue)
- Payment Method
- Reference Number

#### Transaction History
- All loan-related transactions
- Payment confirmations
- Interest calculations
- Balance adjustments

## Commodity Trading System

### Commodity Marketplace (`resources/views/user/commodities/marketplace.blade.php`)
**URL**: `/user/commodity`
**Sidebar Access**: Commodity

#### Marketplace Header
- "Commodity Marketplace" title
- "Browse and purchase available commodities" subtitle
- "My Balance" button (links to balance page)

#### Product Grid Display
**For each commodity**:
- Product image (or default icon)
- Product name and description
- Price (₦X,XXX.XX)
- Available quantity
- Status badge (Active/Inactive)
- "View Details" button

#### Empty State
When no commodities available:
- Illustration icon
- "No commodities available" message
- "Check back later" subtitle

### Commodity Details Page (`resources/views/user/commodities/show.blade.php`)
**URL**: `/user/view_commodity/{id}`
**Access**: Click "View Details" on any commodity

#### Product Display Layout
- **Left Side**: Product image (300px max width)
- **Right Side**: Product information
  - Product name
  - Commodity type badge
  - Price display
  - Available quantity
  - Status indicator
  - Description text

#### Action Buttons
- "Back to Commodities" button (top-right)
- Purchase functionality (if implemented)

### Commodity Balance Page (`resources/views/user/commodities/index.blade.php`)
**URL**: `/user/commodity` (same as marketplace, different view)
**Access**: "My Balance" button from marketplace

#### Balance Overview Cards
- Essential Commodity balance
- Non-Essential Commodity balance
- Electronics balance
- Total available credit

#### Available Commodities Table
**Columns**:
- Commodity name
- Price
- Stock quantity
- Type (Essential/Non-essential)
- Status
- Action (View button)

## Financial Management

### Transaction Report Page (`resources/views/user/transaction_report.blade.php`)
**URL**: `/user/transaction_report`
**Sidebar Access**: Transaction Report

#### Filter Section
**Filter Options**:
- Transaction Type dropdown (All Types, Savings, Shares, Loans, etc.)
- Start Date picker
- End Date picker
- Status dropdown (All Statuses, Completed, Pending, Failed)
- Apply Filters button
- Reset button

#### Export Options
- PDF Export button
- CSV Export option (if available)
- Date range selection
- Print functionality

#### Transactions Table
**Columns**:
- Date (sortable)
- Description (transaction details)
- Amount (₦X,XXX.XX)
- Status (color-coded badges)
- Actions (View Details if available)

**Features**:
- Pagination (10 items per page)
- Sorting capabilities
- Search functionality
- Responsive design

### Savings Withdrawal Page (`resources/views/user/saving_withdrawals/create.blade.php`)
**URL**: `/user/saving_withdrawals/create`
**Sidebar Access**: Request Saving Withdrawal

#### Financial Summary Sidebar
- Total Savings balance
- Shares balance
- Loan balance
- Commodity balances (Essential, Non-essential, Electronics)

#### Withdrawal Form
**Required Fields**:
- Withdrawal Amount (₦0.01 to available balance maximum)
- Notes (optional)

**Features**:
- Real-time balance validation
- Amount formatting (NGN currency)
- JavaScript validation
- Submission confirmation

#### Withdrawal History Table
**Columns**:
- Date
- Amount (₦X,XXX.XX)
- Status (Approved/Rejected/Pending)
- Notes

## Account Management

### Profile Page (`resources/views/user/profile.blade.php`)
**URL**: `/user/profile`
**Sidebar Access**: My Profile

#### Personal Information Section
- Profile photo upload/change
- Full name (read-only, edit via modal)
- Membership ID (read-only)
- Email (read-only, cannot change)
- Phone number (editable)
- Address (editable)

#### Membership Details Section
- Membership status (Active/Inactive/Suspended)
- Membership type (Regular Member, etc.)
- Monthly contribution amount
- Member since date
- Membership benefits list

#### Next of Kin Information
- Full name
- Relationship
- Phone number
- Address
- Edit functionality

#### Financial Summary Cards
- Entrance fee status
- Shares balance
- Savings balance
- Loan balance
- Commodity balances
- Loan interest paid

#### Transaction History Preview
- Recent transactions table
- Pagination
- "View Full Transaction History" link

### Settings Page (`resources/views/user/settings.blade.php`)
**URL**: `/user/settings`
**Sidebar Access**: Settings

#### Personal Information Table
- Full Name (link to profile edit)
- Email (read-only)
- Phone (link to profile edit)
- Address (link to profile edit)

#### Account Security Section
- Change Password functionality
- Password requirements display
- Last password change date

#### Change Password Modal
**Fields**:
- Current Password
- New Password
- Confirm New Password
- Update button
- Cancel button

## Support System

### Support Tickets Page (`resources/views/user/support.blade.php`)
**URL**: `/user/support`
**Sidebar Access**: Support Tickets

#### New Ticket Form Section
**Form Fields**:
- Subject (required)
- Category dropdown (General, Account, Loan, Savings, Shares, Commodity)
- Message (textarea, required)
- Attachment (file upload, optional)
- Send Ticket button

#### My Tickets Table
**Columns**:
- Ticket # (reference number)
- Subject
- Status (Open/Closed/Pending)
- Submitted date
- Action (View Details)

**Features**:
- Status filtering dropdown
- Pagination
- Responsive design

### Ticket Details Page (`resources/views/user/ticket_details.blade.php`)
**URL**: `/user/support/{id}`
**Access**: Click "View Details" on any ticket

#### Ticket Header
- Subject and ticket number
- Creation date and time
- Category badge
- Status indicator
- Close Ticket button (if open)

#### Conversation Thread
- Original ticket message
- Admin replies (highlighted background)
- User replies
- Timestamps for all messages
- File attachments (downloadable)

#### Reply Functionality
- Reply textarea
- File attachment option
- Send Reply button

## Administrative Panel

### Admin Dashboard (`resources/views/admin/index.blade.php`)
**URL**: `/admin/dashboard`
**Access**: Admin login redirect

#### Overview Metrics Cards
- Total Users (with registered members subtitle)
- Active Loans (currently disbursed)
- Total Revenue (all-time earnings)
- Pending Loans (awaiting approval)

#### Recent Activities
- Latest user registrations
- Recent loan approvals
- Pending applications
- System notifications

### Bulk Operations System

#### Monthly Contributions Upload (`app/Http/Controllers/Admin/BulkUpdateController.php`)
**URL**: `/admin/bulk_updates`
**Purpose**: Process monthly member contributions and update financial records

**Key Features**:
- **Sequential Month Processing**: Only allows uploads for the month immediately following the last completed upload
- **Transaction Grouping**: All related transactions are grouped under a single `transaction_group_id` for audit trails
- **Data Validation**: Comprehensive validation of uploaded data including member existence and amount formats
- **Rollback Capability**: Failed uploads can be reversed with full transaction reversal

**Upload Process**:
1. Select Excel/CSV file with member contribution data
2. Choose transaction date (must be next sequential month)
3. Map columns (Staff ID, Name, Savings, Shares, etc.)
4. Preview and validate data
5. Process transactions with automatic balance updates
6. Generate transaction reports

**Supported Transaction Types**:
- Monthly Savings contributions
- Share purchases (max ₦10,000 per contribution)
- Entrance fee payments
- Loan repayments (principal and interest)
- Commodity balance adjustments

#### Manual Transaction Management (`app/Http/Controllers/Admin/ManualTransactionController.php`)
**URL**: `/admin/manual_transactions`
**Purpose**: Administrative corrections and manual financial adjustments

**Transaction Types Available**:
- **Entrance Fee**: One-time membership fee adjustments
- **Shares**: Manual share credit/debit operations
- **Savings**: Manual savings account adjustments
- **Loan Operations**: Disbursements, repayments, interest adjustments
- **Commodity Transactions**: Essential/non-essential goods adjustments
- **Electronics**: Electronic equipment balance modifications

**Business Rule Validation**:
- Loan eligibility checks before disbursements
- Balance limit validations
- Transaction amount limits
- Member status verification

### System Administration Features

#### System Logs (`resources/views/admin/system_logs.blade.php`)
**Features**:
- Application error logging
- User activity monitoring
- Security audit trails
- Performance monitoring
- Log file downloads and cleanup

#### Database Backup (`app/Http/Controllers/Admin/AdministrationController.php`)
**Features**:
- Automated database backups
- Manual backup initiation
- Backup file management
- Restoration capabilities

#### Cache Management
**Features**:
- Application cache clearing
- Route cache management
- Configuration cache reset
- View cache cleanup

### User Management

#### User List Page
**URL**: `/admin/users`
**Features**:
- Search and filter users
- Bulk actions (activate/deactivate)
- Export user data
- User status management

#### Edit User Finances (`resources/views/admin/users/edit_finances.blade.php`)
**URL**: `/admin/users/{id}/edit_finances`
**Features**:
- Manual balance adjustments
- Transaction corrections
- Financial audit trail
- Balance recalculation

### Transaction Management

#### Manual Transactions (`resources/views/admin/manual_transactions/create.blade.php`)
**URL**: `/admin/manual_transactions/create`
**Features**:
- Create transactions for users
- Balance adjustments
- Administrative corrections
- Audit logging

#### Transaction Overview (`resources/views/admin/transaction.blade.php`)
**URL**: `/admin/transactions`
**Features**:
- All system transactions
- Advanced filtering
- Export capabilities
- Transaction status management

### System Administration

#### Bulk Updates (`resources/views/admin/bulk_updates.blade.php`)
**URL**: `/admin/bulk_updates`
**Features**:
- Mass user data updates
- CSV import/export
- Bulk financial adjustments
- System-wide changes

#### System Logs (`resources/views/admin/system_logs.blade.php`)
**URL**: `/admin/system_logs`
**Features**:
- System activity monitoring
- Error logging
- Security audit trail
- Performance monitoring

## Public Pages

### About Us (`resources/views/pages/about.blade.php`)
**URL**: `/about`
**Content**: Company information, mission, team

### Business Rules (`resources/views/pages/business_rules.blade.php`)
**URL**: `/business-rules`
**Content**: Loan eligibility, interest rates, policies

### Contact (`resources/views/pages/contact.blade.php`)
**URL**: `/contact`
**Content**: Contact form, office information, support details

### FAQ (`resources/views/pages/faq.blade.php`)
**URL**: `/faq`
**Content**: Frequently asked questions and answers

### Privacy Policy (`resources/views/pages/privacy.blade.php`)
**URL**: `/privacy`
**Content**: Data protection and privacy information

### Terms of Service (`resources/views/pages/terms.blade.php`)
**URL**: `/terms`
**Content**: Legal terms and conditions

### Cookie Policy (`resources/views/pages/cookie.blade.php`)
**URL**: `/cookie`
**Content**: Cookie usage and privacy information

### Testimonial (`resources/views/pages/testimonial.blade.php`)
**URL**: `/testimonial`
**Content**: User testimonials and reviews

## Error Pages

### 403 Forbidden (`resources/views/errors/403.blade.php`)
**URL**: Access denied pages
**Features**:
- Permission error message
- Return to dashboard link
- Contact support information

### 404 Not Found (`resources/views/errors/404.blade.php`)
**URL**: Invalid URLs
**Features**:
- Page not found message
- Navigation suggestions
- Search functionality

## Mobile Responsiveness

### Responsive Breakpoints
- **Mobile**: < 640px (sm:)
- **Tablet**: 640px - 1024px (md:)
- **Desktop**: > 1024px (lg:)

### Mobile-Specific Features
- Collapsible sidebar navigation
- Touch-friendly button sizes
- Swipe gestures for navigation
- Optimized form layouts
- Mobile-optimized tables (horizontal scroll)

## Keyboard Navigation

### General Shortcuts
- **Tab**: Navigate through form fields and links
- **Enter**: Activate buttons and submit forms
- **Escape**: Close modals and dropdowns
- **Arrow Keys**: Navigate table rows and menu items

### Form Navigation
- **Tab/Shift+Tab**: Move between form fields
- **Enter**: Submit forms
- **Ctrl+Enter**: Submit without validation (where applicable)

## Accessibility Features

### Screen Reader Support
- Alt text for all images
- Proper heading hierarchy (H1-H6)
- ARIA labels for interactive elements
- Semantic HTML structure

### Keyboard Accessibility
- All functions accessible via keyboard
- Focus indicators on interactive elements
- Skip links for main content
- Logical tab order

### Color and Contrast
- High contrast color schemes
- Color-blind friendly palettes
- Sufficient color contrast ratios
- Alternative indicators beyond color

## System Architecture and Data Flow

### Database Schema Overview

#### Core Tables
- **users**: User accounts and authentication
- **members**: Extended member information and eligibility
- **transactions**: General transaction records
- **saving_transactions**: Savings account movements
- **share_transactions**: Share investment records
- **loans**: Loan applications and disbursements
- **loan_payments**: Individual loan repayment records
- **user_commodities**: Commodity purchase balances
- **commodity_transactions**: Commodity purchase records
- **electronics**: Electronics purchase records
- **support_tickets**: Help desk system
- **monthly_uploads**: Bulk upload tracking

#### Transaction-Based Calculations
All financial balances are calculated dynamically from transaction tables rather than stored values:

```
Savings Balance = saving_transactions(credit) - saving_transactions(debit)
Shares Balance = share_transactions(credit) - share_transactions(debit)
Loan Balance = loan.amount - loan_payments(principal_payments)
Interest Owed = (loan.amount × 10%) - loan_payments(interest_payments)
```

### Business Workflows

#### Loan Application Process
1. **Eligibility Check**: Verify 6+ months membership, entrance fee paid
2. **Amount Calculation**: 2 × (Savings + Shares - Liabilities)
3. **Application Submission**: Online form + physical documentation
4. **Admin Review**: Manual approval process
5. **Disbursement**: Funds transfer to member account
6. **Repayment**: Bursary deductions (no fixed schedule)

#### Monthly Contribution Processing
1. **Bulk Upload**: Excel/CSV file with member contributions
2. **Data Validation**: Member existence, amount formats, business rules
3. **Transaction Creation**: Grouped transactions for audit trail
4. **Balance Updates**: Automatic calculation updates
5. **Report Generation**: Processing summaries and error logs

### Financial Calculation Service

#### Key Methods (`app/Services/FinancialCalculationService.php`)
- `calculateSavingsBalance()`: Transaction-based savings calculation
- `calculateSharesBalance()`: Transaction-based shares calculation
- `calculateLoanBalance()`: Active loan principal minus payments
- `calculateLoanInterest()`: Interest owed minus interest payments
- `calculateMaxLoanAmount()`: Eligibility formula implementation
- `getAllBalances()`: Comprehensive balance retrieval

## Troubleshooting

### Common Issues

#### Login Problems
- Check email and password
- Verify Caps Lock status
- Clear browser cache
- Try different browser
- Contact support for account lockout

#### Page Loading Issues
- Check internet connection
- Refresh page (Ctrl+F5 for hard refresh)
- Clear browser cache and cookies
- Disable browser extensions
- Try incognito mode

#### Form Submission Errors
- Verify all required fields completed
- Check file upload size limits
- Validate email format
- Ensure terms acceptance
- Review error messages

#### Transaction Failures
- Verify sufficient balance
- Check account status
- Review transaction limits
- Contact support for unusual errors

#### Bulk Upload Issues
- Verify file format (Excel/CSV)
- Check column mapping
- Ensure sequential month processing
- Validate member data existence
- Review error logs for specific failures

#### Financial Calculation Discrepancies
- Clear application cache
- Check transaction integrity
- Verify manual transaction entries
- Review bulk upload processing
- Contact administrator for balance corrections

### System Maintenance

#### Regular Tasks
- **Cache Clearing**: `php artisan cache:clear`
- **Database Backup**: Automated daily backups
- **Log Rotation**: Monthly log file cleanup
- **Session Cleanup**: Remove expired sessions
- **Storage Optimization**: Clean temporary files

#### Performance Monitoring
- Database query optimization
- Cache hit ratios
- Memory usage monitoring
- Response time tracking
- Error rate monitoring

### Getting Help

#### Support Channels
- **In-App Support**: Support Tickets section
- **Email Support**: support@acics.org
- **Phone Support**: +234-XXX-XXX-XXXX
- **Business Hours**: Monday-Friday, 9AM-5PM WAT

#### Self-Help Resources
- FAQ section
- User documentation
- Video tutorials
- Community forums
- System logs (admin access)

## API Reference

### Key Controllers and Methods

#### User Controllers
- **DashboardController@index**: Main dashboard with financial summaries
- **LoanController@index**: Loan overview and management
- **LoanController@showApplicationForm**: Loan application form display
- **LoanController@processApplication**: Loan application processing
- **TransactionController@index**: Transaction history and filtering
- **ProfileController@index**: User profile management

#### Admin Controllers
- **BulkUpdateController@index**: Bulk operations dashboard
- **BulkUpdateController@upload**: File upload processing
- **ManualTransactionController@index**: Manual transaction management
- **UserController@index**: User management interface
- **LoanController@index**: Loan approval workflow

#### Services
- **FinancialCalculationService**: Core financial calculations
  - `calculateSavingsBalance(User $user)`
  - `calculateSharesBalance(User $user)`
  - `calculateLoanBalance(User $user)`
  - `calculateMaxLoanAmount(User $user)`

### Configuration Files
- **config/business_rules.php**: Business logic configuration
- **config/app.php**: Application configuration
- **config/database.php**: Database connection settings

---

*This comprehensive guide covers all pages, features, and navigation paths in the ACICS system. For the latest updates or specific feature documentation, refer to the in-app help system or contact system administrators.*