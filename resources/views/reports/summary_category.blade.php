@if($summaries)
<div class="p-counter__wrapper bg-white col-5 mx-2 mb-4 p-2">
  <div class="row mx-1 mb-2">
    <div class="col-12 p-0">
      <span class="badge badge-secondary">カテゴリー別 Total</span><br>
    </div>
  </div>
  @foreach($summaries as $category_name=>$summary)  
  @if($summary['total_hour'])
    <div class="row m-0">
        <span class="badge badge-light">{{ $category_name }}</span>
    </div>
    <div class="row mx-2 mb-2">
      <div class="col-8 p-0">
        <small>
          <i class="fas fa-medal" aria-hidden="true"></i>
          達成度<br>
          <span class="p-counter__number">
            {{ $summary['total_hour'] ?? 0 }}
          </span>
          h /
          <span class="p-counter__number">
            {{ $summary['total_count'] ?? 0 }}
          </span>
          回
        </small>
      </div>
      <div class="col-4 p-0">
        <small>
          <i class="far fa-check-circle" aria-hidden="true"></i>
          完了<br>
          <span class="p-counter__number">
            {{ $summary['completed_count'] ?? 0 }}
          </span>
          件
        </small>
      </div>
    </div>
    @endif
    @endforeach
  </div>
@endif
