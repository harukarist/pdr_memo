
    <div class="row justify-content-center">
      <div class="col-md-10">
          <div class="card">
              <div class="row mx-auto">
              @if(isset($records))
                  @foreach($records as $day => $record)
                  <div class="col-lg">
                      <small>{{ $day }}</small><br>
                      @if(isset($record))
                      @foreach($record as $project => $arr)
                          @if(count($arr))
                          @foreach($arr as $var)
                          <div class="mb-1">
                              <small>{{ $project }}</small>
                              <small><mark>{{ $var->hour }}h</mark></small>
                          </div>
                          @endforeach
                          @elseif(!count($arr) && $loop->first)
                          <div class="mb-1">
                          <span class="mr-2">
                              <small><mark>0 h</mark></small>
                          </span>
                          </div>
                          @endif
                      @endforeach
                      @endif
                  </div>
                  @endforeach
              @endif
              </div>
          <div>
      </div>
  </div>
