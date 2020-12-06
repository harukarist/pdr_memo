


  <div class="p-tasklist__title mb-2">
    <i class="fas fa-folder-open" style="color:{{ $current_project->project_color }}" aria-hidden="true"></i>
    {{ $current_project->project_name ?? ''}} のタスクリスト
  </div>

  {{-- 目標 --}}
  @isset($current_project->project_target)
  <div class="p-tasklist__target alert alert-success p-2 mb-2">
    {{ $current_project->project_target ?? ''}} 
  </div>
  @endisset

    
  @if($counter)
  
  <div class="p-counter__wrapper mx-0 mb-4">

    <div class="row m-0">
      <div class="col-12">
        <small>
          <span class="p-counter__number">{{ $counter['days_count'] ?? 0 }}</span> 日目
        </small>
        @if($counter['days_count'])
        <span class="p-counter__number small ml-2">最初の記録：{{ $counter['first_date']->format('Y/m/d') ?? '' }}</span>
        @endif
      </div>
    </div>
    <div class="row m-0 bg-white">
      <div class="col-auto">
        <small>
          <i class="fas fa-medal" aria-hidden="true"></i>
          達成度<br>
          <span class="p-counter__number">
            {{ $counter['reviewed_hours'] ?? 0 }}
          </span>
          h /
          <span class="p-counter__number">
            {{ $counter['reviewed_count'] ?? 0 }}
          </span>
          回
        </small>
      </div>
      <div class="col-auto">
        <small>
          <i class="far fa-check-circle" aria-hidden="true"></i>
          完了<br>
          <span class="p-counter__number">
            {{ $counter['completed_count'] ?? 0 }}
          </span>
          件
        </small>
      </div>
      <div class="col-auto">
        <small>
          <i class="far fa-circle" aria-hidden="true"></i>
          実行中<br>
          <span class="p-counter__number">
            {{ $counter['doing_count'] ?? 0 }}
          </span>
            件
        </small>
      </div>
      <div class="col-auto">
        <small>
          <i class="far fa-clipboard" aria-hidden="true"></i>
          残り<br>
          <span class="p-counter__number">
            {{ $counter['days_count'] ?? 0 }} 
          </span>
          h / 
          <span class="p-counter__number">
            {{ $counter['remained_steps'] ?? 0 }}
          </span>
          回
        </small>
      </div>
      {{-- <div class="col">
        Prep済み<br>
        {{ $counter['prepped_count'] ?? 0 }} 件
      </div>
      <div class="col">
        Prep未設定<br>
        {{ $counter['waiting_count'] ?? 0 }} 件
      </div> --}}
    </div>
  </div>
  @endif

