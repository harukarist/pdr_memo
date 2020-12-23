<div class="border bg-white p-3 mb-3">
  <div class="p-guide__wrapper d-flex">
    {{-- チェックボックス --}}
    <div class="p-guide__checkbox mr-2">
      @if($current_task->status == 4)
      <i class="far fa-check-circle" aria-hidden="true"></i>
      @else
      <i class="far fa-circle" aria-hidden="true"></i>
      @endif
    </div>
    <div class="p-guide__contents text-justify p-0">
      <div class="p-guide__taskname">
        <h6 class="d-inline align-middle">
          {{ $current_task->task_name }}</h6>
        <small class="pl-2"> - {{ $current_task->project->project_name }}</small>
      </div>
      @if(count($current_task->preps))
        @forelse($current_task->preps as $prep)
          <div class="p-guide__prep-detail py-2">
            <small>
              Prep-{{ $loop->iteration }}
              <mark class="mr-2">
              {{ $prep->unit_time }}分 × 
              {{ $prep->estimated_steps }} ＝
              {{ ($prep->unit_time)*($prep->estimated_steps) }}分
              </mark>
            </small>
          </div>
          <div class="p-guide__prep-text" v-pre>
            {!! nl2br(e($prep->prep_text))  !!}
          </div>
        @empty
        @endforelse
      @endif
    </div>
  </div>
</div>
