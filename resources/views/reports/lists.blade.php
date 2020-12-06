<div class="container c-container">
  <section class="mb-5">
    <a href="{{ route('reviews.add') }}" class="btn btn-outline-secondary btn-block mb-4">
      記録を追加
    </a>
  </section>
  <section class="mb-5">
    @if(count($lists))
    @foreach($lists as $list)
    <div class="p-record__wrapper mb-4">
      <div class="p-record__date border-bottom mb-4 px-2 d-flex">
        <h5>{{ $list['target_date'] ?? '' }}</h5>
        <div class="p-record__time">
          @if(isset($list['total_time']))
          @foreach($list['total_time'] as $key=>$val)
          <mark class="">{{ $category[$key]['category_name'] }} {{ round($val/60,1) ?? '0' }} h</mark>
          @endforeach
          @endif
        </div>
      </div>
      <div class="p-record__contents">
        @if(isset($list['tasks']))
        @foreach($list['tasks'] as $task)
          @if(!empty($task))
            <div class="p-record__task-item d-flex bg-white p-2">
              <span class="p-record__checkbox pr-2">
                @if(!empty($task['task_status']) && $task['task_status'] == 4)
                <i class="far fa-check-square icon-checkbox" aria-hidden="true"></i>
                @else
                <i class="far fa-square icon-checkbox" aria-hidden="true"></i>
                @endif
              </span>
              <span>{{ $task['task_name'] ?? '' }}</span>
            </div>
            <div class="p-record__reviews px-4 py-2">
              @if(isset($task['reviews']))
              @foreach($task['reviews'] as $review)
              <p>
                {!! nl2br(e($review['review_text'])) ?? '' !!}<br>
                <mark>{{ $review['category']['category_name'] ?? '' }}</mark>
                <mark>{{ $review['actual_time'] ?? '' }}分</mark>
              </p>  
              @endforeach
              @endif
            </div>
          @endif
        @endforeach
        @endif
      </div>
    </div>
    @endforeach
    @else
    <div class="p-record__wrapper mb-4">
      記録はまだありません
    </div>
    @endif
  </section>
</div>
