<!-- Twitter Notification -->
<li>
    <div class="list-timeline-time">{{ Carbon\Carbon::parse($update->created_at)->diffForHumans() }}</div>
    @if ($update->type == "NEW_REFERRAL")
    <i class="fa fa-thumbs-o-up list-timeline-icon bg-info"></i>
    @else
    <i class="fa fa-coffee list-timeline-icon bg-info"></i>
    @endif
    <div class="list-timeline-content">
        <p class="font-w600">{{ $update->title }}</p>
        <p class="font-s13">{!! $update->content !!}</p>
    </div>
</li>
<!-- END Twitter Notification -->


@if ($update->type == "ABC")
<!-- Code for referring next time -->
<!-- Generic Notification -->
<li>
    <div class="list-timeline-time">4 hrs ago</div>
    <i class="fa fa-briefcase list-timeline-icon bg-city"></i>
    <div class="list-timeline-content">
        <p class="font-w600">+ 3 New Products were added!</p>
        <p class="font-s13">Congratulations!</p>
    </div>
</li>
<!-- END Generic Notification -->

<!-- System Notification -->
<li>
    <div class="list-timeline-time">1 day ago</div>
    <i class="fa fa-check list-timeline-icon bg-success"></i>
    <div class="list-timeline-content">
        <p class="font-w600">Database backup completed!</p>
        <p class="font-s13">Download the <a href="javascript:void(0)">latest backup</a>.</p>
    </div>
</li>
<!-- END System Notification -->

<!-- Facebook Notification -->
<li>
    <div class="list-timeline-time">3 hrs ago</div>
    <i class="fa fa-facebook list-timeline-icon bg-default"></i>
    <div class="list-timeline-content">
        <p class="font-w600">+ 290 Page Likes</p>
        <p class="font-s13">This is great, keep it up!</p>
    </div>
</li>
<!-- END Facebook Notification -->

<!-- Social Notification -->
<li>
    <div class="list-timeline-time">2 days ago</div>
    <i class="fa fa-user-plus list-timeline-icon bg-modern"></i>
    <div class="list-timeline-content">
        <p class="font-w600">+ 3 Friend Requests</p>
        <ul class="nav-users push-10-t push">
            <li>
                <a href="base_pages_profile.html">
                    <img class="img-avatar" src="assets/img/avatars/avatar11.jpg" alt="">
                    <i class="fa fa-circle text-success"></i> Ethan Howard
                    <div class="font-w400 text-muted"><small>Graphic Designer</small></div>
                </a>
            </li>
            <li>
                <a href="base_pages_profile.html">
                    <img class="img-avatar" src="assets/img/avatars/avatar6.jpg" alt="">
                    <i class="fa fa-circle text-warning"></i> Lisa Gordon
                    <div class="font-w400 text-muted"><small>Photographer</small></div>
                </a>
            </li>
            <li>
                <a href="base_pages_profile.html">
                    <img class="img-avatar" src="assets/img/avatars/avatar16.jpg" alt="">
                    <i class="fa fa-circle text-danger"></i> Walter Fox
                    <div class="font-w400 text-muted"><small>UI Designer</small></div>
                </a>
            </li>
        </ul>
    </div>
</li>
<!-- END Social Notification -->

<!-- System Notification -->
<li class="push-5">
    <div class="list-timeline-time">1 week ago</div>
    <i class="fa fa-cog list-timeline-icon bg-primary-dark"></i>
    <div class="list-timeline-content">
        <p class="font-w600">System updated to v2.02</p>
        <p class="font-s13">Check the complete changelog at the <a href="javascript:void(0)">activity page</a>.</p>
    </div>
</li>
<!-- END System Notification -->
@endif