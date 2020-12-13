@if($summaries)
  @foreach($summaries as $project_name=>$summary)  
  @if(isset($summary))
  <div class="p-counter__wrapper bg-white col-5 mx-2 mb-4 p-2">
    <div class="row m-0">
      <small>
        {{ $project_name }}
        <span class="p-counter__number">{{ $summary['days_count'] ?? 0 }}</span> 日目
      </small>
    </div>
    <div class="row m-0">
      <span class="p-counter__number small">最初の達成：{{ $summary['started_at'] ?? '' }}</span>
    </div>

    @if(isset($summary['categories']))
      @foreach($summary['categories'] as $category_name => $category_item)
        {{-- カテゴリー別 --}}
        @if($category_item['total_hour'] )
          <div class="row m-0">
            <div class="col-12 p-0">
              <span class="badge badge-light">{{ $category_name }}</span><br>
            </div>
          </div>
          <div class="row mx-2 mb-1">
              <div class="col-8 p-0">
                <small>
                <i class="fas fa-medal" aria-hidden="true"></i>
                達成度<br>
                <span class="p-counter__number">
                {{ $category_item['total_hour'] }}
                </span> h /
                <span class="p-counter__number">
                {{ $category_item['total_count'] }}
                </span> 回
                </small>
              </div>
              <div class="col-4 p-0">
                <small>
                <i class="far fa-check-circle" aria-hidden="true"></i>
                完了<br>
                <span class="p-counter__number">
                {{ $category_item['completed_count'] }}
                </span> 件<br>
                </small>
              </div>
          </div>
        @endif
      @endforeach
    @endif

    <div class="bg-light mt-2 p-1 align-self-end">
      <div class="row m-0">
        <div class="col-12 p-0">
          <span class="badge badge-secondary">Total</span><br>
        </div>
      </div>
      <div class="row m-0">
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
    </div>
  </div>
  @endif
  @endforeach
@endif
