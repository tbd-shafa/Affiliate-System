<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AffiliateUser;
use App\Models\AffiliateReferral;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use App\Models\Commission;
use App\Models\User;
use App\Models\Role;
use App\Models\UserDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\DB;


class AffiliateController extends Controller
{
    public function create()
    {
        return view('affiliate.create');
    }
    public function panel()
    {
        return view('affiliate.affiliate-panel');
    }

    public function link()
    {
        if (request()->ajax()) {
            return view('affiliate.affiliate-link'); // Return partial view for AJAX
        }
        return view('affiliate.affiliate-link');
    }
    
    public function store(Request $request)
    {

        $request->validate([
            'address' => 'required|string|max:255',
            'acc_name' => 'required|string|max:255',
            'acc_no' => 'required|string|max:25',
            //'phone_number' => 'required|string|max:15',
            'phone_number' => [
                'required',
                'string',
                'regex:/^\+?[1-9]\d{1,14}$/', // Phone number validation regex
                'max:15', // E.164 format (max length 15)
            ],
            'bank_name' => 'required|string|max:255',
            'branch_address' => 'required|string|max:255',
        ]);

        dd(1);
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
        $pendingRequests = UserDetail::where('status', 'pending')->latest()->paginate(10);
        return view('affiliate.affiliate_requests', compact('pendingRequests'));
    }

    public function approveRequest(Request $request, $id)
    {

        // Determine the action type
        $actionType = $request->input('action_type');

        if ($actionType === 'approve') {
            // Validate percentage field
            $request->validate([
                'percentage' => 'required|numeric|min:0|max:100',
            ], [
                'percentage.required' => 'The percentage field is required when approving.',
            ]);

            // Retrieve the affiliate request from the user_details table
            $affiliateUser = UserDetail::findOrFail($id);

            // Generate a unique affiliate code
            $affiliateCode = strtoupper(Str::random(10));
            // Ensure the affiliate code is unique
            while (UserDetail::where('affiliate_code', $affiliateCode)->exists()) {
               
                $affiliateCode = strtoupper(Str::random(10));
            }

            // Update the status and set a unique affiliate code
            $affiliateUser->update([
                'percentage_value' =>$request->percentage,
                'status' => 'approved',
                'affiliate_code' => $affiliateCode, // Save the unique code
            ]);

            // Get the email address of the user associated with the affiliate
            $userEmail = $affiliateUser->user->email;

            // Get the email address of the user associated with the affiliate
            $user = $affiliateUser->user;

            // Find the 'affiliate_user' role ID
            $affiliateRoleId = Role::where('name', 'affiliate_user')->value('id');

            if ($affiliateRoleId) {
                // insert new row in the 'role_user' table
                    DB::table('role_user')->insert([
                        'user_id' => $user->id,  // Link to the user created
                        'role_id' => $affiliateRoleId, // Role ID (admin role)
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            }

            // $affiliateRoleId = Role::where('name', 'affiliate_user')->value('id');
            // if ($affiliateRoleId) {
            //     // Get the user's current roles
            //     $currentRoles = $user->roles;
    
            //     // Check if the user has the 'user' role and update it
            //     foreach ($currentRoles as $role) {
            //         if ($role->name === 'user') {
            //             // Update the role_id in 'role_user' table
            //             DB::table('role_user')
            //                 ->where('user_id', $user->id)
            //                 ->where('role_id', $role->id)
            //                 ->update(['role_id' => $affiliateRoleId, 'updated_at' => now()]);
                        
            //             // No need to insert a new row, just update the existing one
            //             break;
            //         }
            //     }
            // }

            // Send approval email
            $this->sendApprovalEmail($user->email, true);
            // Send approval email
            // $this->sendApprovalEmail($userEmail, true);

            return redirect()->route('affiliate.requests')->with('success', 'Affiliate Request approved successfully.');
        }

        return redirect()->back()->with('error', 'Invalid action type.');
    }


    public function rejectRequest($id)
    {
        

        $affiliateUser = UserDetail::findOrFail($id);

        // Update the status and set a unique affiliate code
        $affiliateUser->update([
            'status' => 'rejected',
        ]);

        // Get the email address of the user associated with the affiliate
        $userEmail = $affiliateUser->user->email;

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
        $totalCommission = Commission::where('affiliate_user_id', $userId)->sum('earn_amount');
       
        return view('affiliate.commission_balance', compact('totalCommission'));
    }


    public function referredUsers()
    {
        $userId = auth()->user()->id;

        // $referredUsers = User::whereIn('id', function ($query) use ($userId) {
        //     $query->select('user_id')
        //         ->from('affiliate_referrals')
        //         ->where('referrer_id', $userId); // Match the referrer_id
        // })->paginate(10);
        
        $referredUsers = User::whereIn('users.id', function ($query) use ($userId) {
            $query->select('user_id')
                ->from('affiliate_referrals')
                ->where('referrer_id', $userId); // Match the referrer_id
        })
        ->leftJoin('commissions', 'users.id', '=', 'commissions.user_id') // Join commissions to get earned_amount
        ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'commissions.earn_amount') // Select the columns you need
        ->latest()->paginate(10);
        
        return view('affiliate.referred_users', compact('referredUsers'));
    }


    public function earnHistory()
    {
        $userId = auth()->user()->id;
        $earnHistory = Commission::where('affiliate_user_id', $userId)->latest()->paginate(10);

        return view('affiliate.earn_history', compact('earnHistory'));
    }
}
