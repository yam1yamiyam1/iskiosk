@extends('layouts.kiosk')

@section('content')
<div id="fullscreenCarousel" class="fullscreen-carousel">
    <div class="carousel-slide active">
        <img src="{{ asset('assets/img/carousel1.jpg') }}" alt="Slide 1">
    </div>
    <div class="carousel-slide">
        <img src="{{ asset('assets/img/carousel2.jpeg') }}" alt="Slide 2">
    </div>

    <div id="carouselReminder" class="carousel-reminder">
        Touch or click to go to home page
    </div>
</div>

<style>
.fullscreen-carousel {
    position: fixed;
    top: 0; left: 0;
    width: 100vw; height: 100vh;
    background: #000;
    overflow: hidden;
    z-index: 9999;
}
.carousel-slide {
    display: none;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0; left: 0;
}
.carousel-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.carousel-slide.active {
    display: block;
    animation: fade 1s;
}
@keyframes fade {
    from {opacity:0;} 
    to {opacity:1;}
}

.carousel-reminder {
    position: absolute;
    bottom: 5%;
    width: 100%;
    text-align: center;
    font-size: 2rem;
    color: #fff;
    text-shadow: 1px 1px 5px #000;
    animation: blink 1.2s infinite;
    z-index: 10000;
}
@keyframes blink {
    0%, 50%, 100% { opacity: 1; }
    25%, 75% { opacity: 0; }
}
</style>

<script>
const slides = document.querySelectorAll('.carousel-slide');
let currentSlide = 0;
const totalSlides = slides.length;

function showSlide(index){
    slides.forEach((s,i)=>s.classList.toggle('active', i===index));
}

const slideInterval = setInterval(()=>{
    currentSlide = (currentSlide+1)%totalSlides;
    showSlide(currentSlide);
},5000);

showSlide(currentSlide);

function exitCarousel(){
    clearInterval(slideInterval);
    const reminder = document.getElementById('carouselReminder');
    if(reminder) reminder.style.display = 'none';
    window.location.href = "{{ route('kiosk.home') }}";
}

['click','touchstart','keydown'].forEach(evt=>{
    document.addEventListener(evt, exitCarousel,{once:true});
});
</script>


<script>
const ws = new WebSocket('ws://localhost:8081');

function formatID(raw) {
  if (!raw) return raw;
  return raw;
}

ws.addEventListener('message', (e) => {
  let msg = e.data.trim();

  console.log("📩 Home scan:", msg);

  if (msg.startsWith('USR')) {
    console.log("🧾 Scanned ID on home:", msg);

    window.location.href = "{{ route('kiosk.submit') }}?scan=" + encodeURIComponent(msg);
  }
});
</script>
@endsection