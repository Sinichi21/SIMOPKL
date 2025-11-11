<?php

namespace App\Http\Controllers;

use App\Models\Awardee;
use App\Models\User;
use App\Models\Complaint;
use App\Models\ComplaintType;
use App\Models\Faq;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminLandingPageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $awardeeCount = User::where('role_id', '!=', 1) ->whereHas('awardee') ->where('is_registered', '=', 1)->where('users.status', '=', 1)->count();
        $approvalCount = User::where('role_id', '=', '1')->where('is_registered', '=', 1)->count();
        $complaintCount = Complaint::all()->count();
        $faqCount = Faq::all()->count();
        $complaintTypes = ComplaintType::withCount('complaints')->get();

        // Menghitung data peminjamam buku selama 6 bulan terakhir
        // Tentukan tanggal 6 bulan yang lalu
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();

        // Query untuk menghitung jumlah record yang dikelompokkan berdasarkan bulan
        $complaintInLastSixMonth = Complaint::where('created_at', '>=', $sixMonthsAgo)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->get();

        return view('admin.index')
            ->with('awardeeCount', $awardeeCount)
            ->with('complaintCount', $complaintCount)
            ->with('faqCount', $faqCount)
            ->with('complaintInLastSixMonth', $complaintInLastSixMonth)
            ->with('complaintTypes', $complaintTypes)
            ->with('approvalCount', $approvalCount);
    }
}
