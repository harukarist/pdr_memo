<div class="col-md-10">
  <section class="mb-5">
    <a href="{{ route('reviews.add') }}" class="btn btn-outline-secondary btn-block mb-4">
      記録を追加
    </a>
  </section>
  <section class="mb-5">
    @php
    // dd(count($lists))
    @endphp
    @if(isset($lists))
    @foreach($lists as $date => $reviews)
      @if(isset($reviews[0]))
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
      @elseif(empty($reviews[0]) && $loop->first)
      <div class="p-record__wrapper text-center mb-4">
        記録はまだありません
      </div>
      @endif
    @endforeach
    @else
    @endif
  </section>
</div>
