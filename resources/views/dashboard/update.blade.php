<!-- Twitter Notification -->
<li>
    <div class="list-timeline-time">{{ $update->created_at }}</div>
    @if ($update->type == "NEW_REFERRAL")
    <i class="fa fa-thumbs-o-up list-timeline-icon bg-info"></i>
    @else
    <i class="fa fa-coffee list-timeline-icon bg-info"></i>
    <div class="list-timeline-content">
        <p class="font-w600">{{ $update->title }}</p>
        <p class="font-s13">{{ $update->content }}</p>
    </div>
</li>
<!-- END Twitter Notification -->