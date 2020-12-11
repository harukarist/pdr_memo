@if($summaries)
<div class="p-counter__wrapper mx-2 mb-4">
  @foreach($summaries as $category_name=>$summary)  
  @if($summary['total_hour'])
    <div class="row m-0 bg-white">
        <span class="badge badge-light">{{ $category_name }}</span>
    </div>
    <div class="row m-0 bg-white">
      <div class="col-auto">
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
      </div>
      <div class="col-auto">
          <i class="far fa-check-circle" aria-hidden="true"></i>
          完了<br>
          <span class="p-counter__number">
            {{ $summary['completed_count'] ?? 0 }}
          </span>
          件
      </div>
    </div>
    @endif
    @endforeach
  </div>
@endif
