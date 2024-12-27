<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AffiliateUser;
use App\Models\AffiliateReferral;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\Commission;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AffiliateController extends Controller
{
    public function create()
    {
        return view('affiliate.create');
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'address' => 'required|string|max:255',
            'acc_name' => 'required|string|max:255',
            'acc_no' => 'required|string|max:20',
            'phone_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'branch_address' => 'required|string|max:255',
        ]);
        // Fetch the authenticated user
        $user = auth()->user();
        
        // Update existing user details if they exist
        $updated = \App\Models\UserDetail::where('user_id', $user->id)->update([
            'address' => $request->input('address'),
            'acc_name' => $request->input('acc_name'),
            'acc_no' => $request->input('acc_no'),
            'phone_number' => $request->input('phone_number'),
            'bank_name' => $request->input('bank_name'),
            'branch_address' => $request->input('branch_address'),
            'status' => 'pending', // Set status to pending
            'affiliate_code' => null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Affiliate request submitted successfully!');
    }
    public function showPendingRequests()
    {
        //$pendingRequests = AffiliateUser::where('status', 'pending')->get();
        $pendingRequests = AffiliateUser::where('status', 'pending')->paginate(10);
        return view('affiliate.affiliate_requests', compact('pendingRequests'));
    }


    public function approveRequest(Request $request, $id)
    {
        // Determine the action type
        $actionType = $request->input('action_type');

        // Fetch affiliate user
        $affiliateUser = AffiliateUser::findOrFail($id);

        // Get the email address of the user associated with the affiliate
        $userEmail = $affiliateUser->user->email;
        $referralCode = encrypt($affiliateUser->user_id);
        if ($actionType === 'submit_without_percentage') {

            // Generate a unique registration link
            $uniqueAffiliateLink = route('register') . '?referrer=' . $referralCode;

            // Update affiliate user status and link
            $affiliateUser->update([
                'status' => 'approved',
                'affiliate_link' => $uniqueAffiliateLink,
            ]);
            $affiliateUser->user->update([
                'role' => 'affiliate_user',
            ]);
            Setting::updateOrCreate(
                ['key' => $affiliateUser->user_id],
                ['value' => 10]
            );
            // Send approval email
            $this->sendApprovalEmail($userEmail, true);

            return redirect()->route('affiliate.requests')->with('success', 'Affiliate approved successfully without setting a percentage.');
        }

        if ($actionType === 'approve') {
            // Validate percentage field
            $request->validate([
                'percentage' => 'required|numeric|min:0|max:100',
            ], [
                'percentage.required' => 'The percentage field is required when approving.',
            ]);

            // Generate a unique registration link
            $uniqueAffiliateLink = route('register') . '?referrer=' . $referralCode;

            // Update affiliate user status and link
            $affiliateUser->update([
                'status' => 'approved',
                'affiliate_link' => $uniqueAffiliateLink,
            ]);

            $affiliateUser->user->update([
                'role' => 'affiliate_user',
            ]);

            // Save percentage in settings
            Setting::updateOrCreate(
                ['key' => $affiliateUser->user_id],
                ['value' => $request->percentage]
            );

            // Send approval email
            $this->sendApprovalEmail($userEmail, true);

            return redirect()->route('affiliate.requests')->with('success', 'Affiliate approved successfully with percentage set.');
        }

        return redirect()->back()->with('error', 'Invalid action type.');
    }

    public function rejectRequest($id)
    {
        $affiliateUser = AffiliateUser::findOrFail($id);
        $userEmail = $affiliateUser->user->email;
        // Update status to rejected
        $affiliateUser->update(['status' => 'rejected']);
        // Send rejection email
        $this->sendApprovalEmail($userEmail, false);

        return redirect()->route('affiliate.requests')->with('success', 'Affiliate Request Rejected Successfully!');
    }

    private function sendApprovalEmail($email, $isApproved)
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp-relay.brevo.com';
            $mail->SMTPAuth = true;
            $mail->Username = '789677002@smtp-brevo.com';
            $mail->Password = 'xsmtpsib-f5a5f0b15bf01cfa4a13723972abe13bfafd0d9bec31ad14e77a2c4326206ed1-2Ysh06Dpyx7GIbBa';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('shafa@technobd.com', 'Admin');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Affiliate Request Approval Mail';
            // Check if the email is for approval or rejection
            if ($isApproved) {
                $mail->Body = "
                <h2>Affiliate Request Approval</h2>
                <p>Congratulations! Your affiliate request has been approved by the admin.</p>
            ";
            } else {
                $mail->Body = "
                <h2>Affiliate Request Rejected</h2>
                <p>Sorry, your affiliate request has been rejected by the admin.</p>
            ";
            }

            $mail->send();
        } catch (Exception $e) {
            // Log error or handle failure
            \Log::error('Failed to send email: ' . $mail->ErrorInfo);
        }
    }

    public function commissionBalance()
    {
        $userId = auth()->user()->id;
        $totalCommission = Commission::where('affiliate_user_id', $userId)->sum('amount');

        return view('affiliate.commission_balance', compact('totalCommission'));
    }


    public function referredUsers()
    {
        $userId = auth()->user()->id;

        // Get the list of users whose `user_id` exists in the `commissions` table and matches the authenticated user as the affiliate
        // $referredUsers = User::whereHas('commissions', function ($query) use ($userId) {
        //     $query->where('affiliate_user_id', $userId); // Match affiliate_user_id with the logged-in user
        // })->paginate(10);
        $referredUsers = User::whereIn('id', function ($query) use ($userId) {
            $query->select('user_id')
                ->from('affiliate_referrals')
                ->where('referrer_id', $userId); // Match the referrer_id
        })->paginate(10);

        return view('affiliate.referred_users', compact('referredUsers'));
    }


    public function earnHistory()
    {
        $userId = auth()->user()->id;
        $earnHistory = Commission::where('affiliate_user_id', $userId)->orderBy('created_at', 'desc')->paginate(10);

        return view('affiliate.earn_history', compact('earnHistory'));
    }
}
