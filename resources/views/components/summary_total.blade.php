<div class="row justify-content-center mb-2">   
  @if(isset($summaries['total']))
    <h5 class="mr-2">Day {{ $summaries['total']['days_count'] ?? 1 }} </h5>
    <p>{{ $summaries['total']['started_at'] ?? '' }}〜{{ $targetDay ?? '' }}</p>
  @endif
</div>
<div class="row justify-content-center mb-2">   
  @if(isset($summaries['categories']))
<span class="badge badge-secondary">カテゴリー別 Total</span><br>
      @forelse($summaries['categories'] as $category_name=>$total_hour)  
          @if($total_hour)
              <span class="badge badge-light">{{ $category_name ?? ''}}</span>
              <span class="p-counter__number">
                  {{ $total_hour ?? '' }}
              </span>
              h
          @endif
      @empty
      @endforelse
  @endif
</div>
<div class="row justify-content-center mb-2">
  @if(isset($summaries['projects']))
  <span class="badge badge-secondary">プロジェクト別 Total</span><br>
      @forelse($summaries['projects'] as $project_name=>$total_hour)  
          @if($total_hour)
              <span class="badge badge-light">{{ $project_name ?? ''}}</span>
              <span class="p-counter__number">
                  {{ $total_hour ?? '' }}
              </span>
              h
          @endif
      @empty
      @endforelse
  @endif
</div>
<div class="row justify-content-center mb-2">
  <a class="small" href="{{ route('totals.custom') }}">合計時間を追加</a>
</div>
