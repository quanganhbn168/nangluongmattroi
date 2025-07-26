<!-- Slider main container -->
<div class="swiper slider">
  <!-- Additional required wrapper -->
    <div class="swiper-wrapper">
    <!-- Slides -->
        @foreach($slides as $key => $slide)
        <div class="swiper-slide">
        	<img
        	class="swiper-lazy"
        	data-src="{{asset($slide->image)}}"
        	src="{{asset($slide->image)}}"
        	alt="{{$slide->name}}"
        	title="{{$slide->name}}"
        	>
        </div>
        @endforeach
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>