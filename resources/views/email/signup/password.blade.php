

Hey {{ $name }}!

@if ($mode === "free_trial")

<p>Thank you for signing up for the free-trial of Morfix!<p>
<p>You will be entitled to our growth hacking automation functions: 
    Auto-Like & Auto-Follow, non-watermarked Automated Direct Messages as well 
    as non-watermarked Captions for our Post Scheduling function.</p>
<p>To begin the free-trial of Morfix, login to your account using the following details:<p>
    
@elseif ($mode === "premium")

<p>Here's your newly created Premium account for Morfix!<p>
<p>Using this account you are entitled to our growth hacking automation functions: 
    Auto-Like, Auto-Comment & Auto-Follow, non-watermarked Automated Direct Messages as well 
    as non-watermarked Captions for our Post Scheduling function.</p>
<p>In addition, you will be able to start affiliating for Morfix with 
    each referral gaining you $20 per month as long as you and them stay subscribed!</p>
<p>To get started using Morfix! Login to your account using the following details:<p>
    
@endif
<p>
    Username: <b>{{ $email }}</b>
    <br/>
    Password: <b>{{ $password }}</b>
</p>

<br/>
Thank you! 
<br/>
Morfix Team