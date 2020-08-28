<div class="modal fade" id="confirm_modal" data-id="" data-param="" tabindex="-1" role="dialog" style="z-index:1111;">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ $confirm_title }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>{{ $confirm_body }}</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" onclick="confirmed()">확인</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="canceled()">취소</button>
			</div>
		</div>
	</div>
</div>