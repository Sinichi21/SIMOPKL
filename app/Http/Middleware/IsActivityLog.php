<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class IsActivityLog
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $actionMethod = $request->route()->getActionMethod();
        $routeName = $request->route()->getName();
        $path = $request->path();

        // Daftar deskripsi khusus untuk setiap route
        $routeDescriptions = [
            'admin.index' => 'Viewing Admin Dashboard',

            // Landing Page
            'landingpage.carolusel' => 'Viewing Carousel Settings',
            'landingpage.carouselsave' => 'Saving Carousel Settings',
            'landingpage.carouselupload' => 'Uploading Carousel Image',
            'landingpage.sosialmedia' => 'Viewing Social Media Settings',
            'landingpage.sosialmediasave' => 'Saving Social Media Settings',
            'landingpage.sosialmediacancel' => 'Cancelling Social Media Changes',
            'landingpage.iconmenu' => 'Viewing Icon Menu Settings',
            'landingpage.iconmenu.save' => 'Saving Icon Menu Settings',
            'landingpage.iconmenu.upload' => 'Uploading Icon Menu Image',
            'landingpage.tentang' => 'Viewing About Page Settings',
            'landingpage.tentangupdate' => 'Updating About Page',
            'landingpage.kontak' => 'Viewing Contact Settings',
            'landingpage.kontaksave' => 'Saving Contact Information',
            'landingpage.kontakcancel' => 'Cancelling Contact Changes',

            // User Management
            'user.index' => 'Viewing User List',
            'user.approve' => 'Viewing User Approval List',
            'user.approve.status' => 'Updating User Approval Status',
            'user.create' => 'Creating New User',
            'user.store' => 'Saving New User',
            'user.delete' => 'Deleting User',
            'user.update' => 'Updating User Information',
            'user.update.status' => 'Updating User Status',
            'user.show' => 'Viewing User Details',
            'user.edit' => 'Editing User Information',

            // Master Data
            'faculty.index' => 'Viewing Faculty List',
            'faculty.create' => 'Creating New Faculty',
            'faculty.update' => 'Updating Faculty Information',
            'faculty.delete' => 'Deleting Faculty',

            'studyProgram.index' => 'Viewing Study Program List',
            'studyProgram.create' => 'Creating New Study Program',
            'studyProgram.update' => 'Updating Study Program Information',
            'studyProgram.delete' => 'Deleting Study Program',

            'complaintType.index' => 'Viewing Complaint Type List',
            'complaintType.create' => 'Creating New Complaint Type',
            'complaintType.update' => 'Updating Complaint Type Information',
            'complaintType.delete' => 'Deleting Complaint Type',

            // Log Activities
            'admin.logs' => 'Viewing Log Activities',
            'logs.delete' => 'Deleting Log Activities',

            // Profile
            'admin.profile' => 'Viewing Profile',
            'admin.profile.update' => 'Updating Profile',

            // FAQ
            'faq.index' => 'Viewing FAQ List',
            'faq.create' => 'Creating New FAQ',
            'faq.store' => 'Saving New FAQ',
            'faq.delete' => 'Deleting FAQ',
            'faq.update' => 'Updating FAQ Information',
            'faq.update.status' => 'Updating FAQ Status',
            'faq.upload' => 'Uploading FAQ File',
            'faq.upload.delete' => 'Deleting Uploaded FAQ File',
            'faq.show' => 'Viewing FAQ Details',
            'faq.edit' => 'Editing FAQ Information',

            // Complaint Management
            'admin.complaint.index' => 'Viewing Complaint List',
            // 'report.pdf' => 'Generating Complaint Report in PDF',
            // 'report.excel' => 'Generating Complaint Report in Excel',
            'admin.complaint.show' => 'Viewing Complaint Details',

            // Thread Management
            'admin.thread.show' => 'Viewing Thread Details',

            // Auth Actions
            'login' => 'User accessing login page',
            'register.index' => 'Viewing registration page',
            'authenticate' => 'Authenticating user',
        ];

        $routeModules = [
            'admin.index' => 'Admin Dashboard',
            'login' => 'Authentication',
            'register.index' => 'Registration',
            'authenticate' => 'Authentication',

            // Landing Page
            'landingpage.carolusel' => 'Carousel',
            'landingpage.carouselsave' => 'Carousel',
            'landingpage.carouselupload' => 'Carousel',
            'landingpage.sosialmedia' => 'Social Media',
            'landingpage.sosialmediasave' => 'Social Media',
            'landingpage.sosialmediacancel' => 'Social Media',
            'landingpage.iconmenu' => 'Icon Menu',
            'landingpage.iconmenu.save' => 'Icon Menu',
            'landingpage.iconmenu.upload' => 'Icon Menu',
            'landingpage.tentang' => 'About Page',
            'landingpage.tentangupdate' => 'About Page',
            'landingpage.kontak' => 'Contact',
            'landingpage.kontaksave' => 'Contact',
            'landingpage.kontakcancel' => 'Contact',

            // User Management
            'user.index' => 'User Management',
            'user.approve' => 'User Management',
            'user.approve.status' => 'User Management',
            'user.create' => 'User Management',
            'user.store' => 'User Management',
            'user.delete' => 'User Management',
            'user.update' => 'User Management',
            'user.update.status' => 'User Management',
            'user.show' => 'User Management',
            'user.edit' => 'User Management',

            // Master Data
            'faculty.index' => 'Faculty',
            'faculty.create' => 'Faculty',
            'faculty.update' => 'Faculty',
            'faculty.delete' => 'Faculty',

            'studyProgram.index' => 'Study Program',
            'studyProgram.create' => 'Study Program',
            'studyProgram.update' => 'Study Program',
            'studyProgram.delete' => 'Study Program',

            'complaintType.index' => 'Complaint Type',
            'complaintType.create' => 'Complaint Type',
            'complaintType.update' => 'Complaint Type',
            'complaintType.delete' => 'Complaint Type',

            // Log Activities
            'admin.logs' => 'Log Activities',
            'logs.delete' => 'Log Activities',

            // Profile
            'admin.profile' => 'Profile',
            'admin.profile.update' => 'Profile',

            // FAQ
            'faq.index' => 'FAQ',
            'faq.create' => 'FAQ',
            'faq.store' => 'FAQ',
            'faq.delete' => 'FAQ',
            'faq.update' => 'FAQ',
            'faq.update.status' => 'FAQ',
            'faq.upload' => 'FAQ',
            'faq.upload.delete' => 'FAQ',
            'faq.show' => 'FAQ',
            'faq.edit' => 'FAQ',

            // Complaint Management
            'admin.complaint.index' => 'Complaint',
            'admin.complaint.store' => 'Complaint',
            'report.pdf' => 'Complaint Report',
            'report.excel' => 'Complaint Report',
            'admin.complaint.show' => 'Complaint',
            'admin.complaint.delete' => 'Complaint',

            // Thread Management
            'admin.thread.show' => 'Thread Management',

            // Auth Actions
            'auth.login' => 'Authentication',
            'auth.logout' => 'Authentication',
        ];

        // Aksi yang diizinkan untuk dicatat
        $allowedActions = [
            'index'             => 'View',
            'updateProfile'     => 'Update Profile',
            'resetPasswordStore'=> 'Reset Password',
            'updateStatus'      => 'Update Status',
            'store'             => 'Create',
            'update'            => 'Update',
            'delete '           => 'Delete',
            'approve'           => 'Approve',
            'authenticateAdmin' => 'Login',
            'logout'            => 'Logout'
        ];

        // Tentukan deskripsi log berdasarkan aksi dan route
        $action = $allowedActions[$actionMethod] ?? null;
        $description = $routeDescriptions[$routeName] ?? 'Performing action on module';
        $module = $routeModules[$routeName] ?? 'General';

        if ($routeName == 'admin.thread.show') {
            $threadId = $request->route('thread');
            $description .= " (Thread ID: $threadId)";
        }

        // Hanya log jika aksi ada dalam daftar yang diizinkan
        if ($action) {
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'module' => $module,
                'description' => $description,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $next($request);
    }

}
