    
  @if($counter)
  
  <div class="p-tasklist__counter-wrapper mx-0 mb-4">

    <div class="row m-0">
      <div class="col-12">
        <small>
          <span class="p-counter__number">{{ $counter['days_count'] ?? 1 }}</span> 日目
        </small>
        @if($counter['days_count'])
        <span class="p-counter__number small ml-2">最初の達成：{{ $counter['started_at'] ?? '' }}</span>
        @endif
      </div>
    </div>
    <div class="row m-0 bg-white">
      <div class="col-auto">
        <small>
          <i class="fas fa-medal" aria-hidden="true"></i>
          達成度<br>
          <span class="p-counter__number">
            {{ $counter['reviewed_hour'] ?? 0 }}
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
            {{ $counter['remained_hour'] ?? 0 }} 
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

