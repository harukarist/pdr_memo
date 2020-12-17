<div class="col-md-10">
  <section class="mb-5">
    <a href="{{ route('records.add',['date'=>$date]) }}" class="btn btn-outline-secondary btn-block mb-4">
      <i class="fas fa-plus mr-1" aria-hidden="true"></i>記録を追加
    </a>
  </section>
  <section class="mb-5">
    @php
    // dd($lists)
    @endphp
    @foreach($lists as $date => $reviews)
    @if(count($reviews))
      {{-- タスクリスト --}}
      <div class="p-record__wrapper mb-4">
        <div class="p-record__date border-bottom mb-4 px-2 d-flex">
          <h5>{{ $date ?? '' }}</h5>
        </div>
        <div class="p-record__contents">
          @component('reports.item')
          @slot('reviews',$reviews)
          @endcomponent
        </div>
      </div>
    @else
      <div class="p-record__wrapper mb-4">
        <div class="p-record__date border-bottom mb-4 px-2 d-flex">
          <h5>{{ $date ?? '' }}</h5>
        </div>
        <div class="p-record__contents">
          <div class="p-report__reviews px-4 py-2 mb-3">
          記録はまだありません
          </div>
        </div>
      </div>
    @endif
  @endforeach
  </section>
</div>
