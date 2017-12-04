<!-- Twitter Notification -->
<li>
	<div class="list-timeline-time">{{ Carbon\Carbon::parse($update->created_at)->diffForHumans() }}</div>

	@if ($update->type == "NEW_REFERRAL")
		<i class="fa fa-thumbs-o-up list-timeline-icon bg-info"></i>
	@elseif ($update->type == "PRO_OTO_UPSELL")
		<i class="fa fa-level-up list-timeline-icon bg-info"></i>
	@elseif ($update->type == "SYSTEM_UPGRADE")
		<i class="fa fa-cog list-timeline-icon bg-danger"></i>
	@else
		<i class="fa fa-coffee list-timeline-icon bg-info"></i>
	@endif

	<div class="list-timeline-content">
		<p class="font-w600">{{ $update->title }}</p>
		<p class="font-s13">{!! $update->content !!}</p>
	</div>
</li>
<!-- END Twitter Notification -->