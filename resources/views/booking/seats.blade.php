@extends('layouts.app')

@section('content')
<div class="container py-3 mb-5">
    <div class="card border-0 shadow-sm rounded-4 p-3">
        <div class="screen-container">
            <div class="screen-arc"></div>
            <p class="caption mt-2">Screen</p>
        </div>

        <div class="text-center overflow-auto">
            <div class="seat-grid-wrapper">
                @for ($r = 0; $r < $showtime->hall->number_of_rows; $r++)
                    @php $rowLetter = chr(65 + $r); @endphp
                    <div class="seat-row">
                        <div class="row-label">{{ $rowLetter }}</div>
                        
                        @for ($s = 1; $s <= $showtime->hall->seats_per_row; $s++)
                            @php 
                                $seatCode = $rowLetter . $s; 
                                $isTaken = in_array($seatCode, $takenSeats);
                            @endphp
                            
                            <div class="seat {{ $isTaken ? 'taken' : 'available' }}" 
                                 data-code="{{ $seatCode }}"
                                 title="{{ $isTaken ? 'Reserved' : 'Seat ' . $seatCode }}">
                            </div>

                            @if ($s == ceil($showtime->hall->seats_per_row / 2))
                                <div style="width: 15px;"></div>
                            @endif
                        @endfor

                        <div class="row-label">{{ $rowLetter }}</div>
                    </div>
                @endfor
            </div>
        </div>

        <div class="d-flex justify-content-center gap-4 mt-4 border-top pt-3">
            <div class="d-flex align-items-center gap-2">
                <div class="seat available" style="width:18px; height:18px;"></div>
                <span class="caption">Available</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="seat selected" style="width:18px; height:18px;"></div>
                <span class="caption">Selected</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="seat taken" style="width:18px; height:18px;"></div>
                <span class="caption">Taken</span>
            </div>
        </div>
    </div>
</div>

<div class="checkout-bar border-top">
    <div class="container d-flex align-items-center justify-content-between">
        <div style="min-width: 150px;">
            <p class="caption mb-0">Total Amount</p>
            <div class="price-medium">₱ <span id="price-display">0.00</span></div>
        </div>

        <div class="flex-grow-1 px-4" style="max-height: 80px; overflow-y: auto;">
            <div id="selected-seats-list" class="d-flex flex-wrap gap-2 justify-content-center"></div>
        </div>

        <div style="min-width: 200px;" class="text-end">
            <form action="{{ route('checkout.payment') }}" method="POST">
                @csrf
                <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
                <input type="hidden" name="selected_seats" id="seats-input">
                <button type="submit" id="submit-btn" class="btn btn-warning px-5 py-3 rounded-3 fw-bold shadow-sm" disabled>
                    Confirm Selection →
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-text: #1E293B;
        --secondary-text: #64748B;
        --swift-blue: #004AAD;
        --seat-available: #E2E8F0;
        --seat-taken: #8492a5;
        --seat-selected: #FFC107;
        --slate-300: #CBD5E1;
    }

    /* Typography */
    .caption { 
        font-size: 11px; 
        font-weight: 500; 
        color: var(--secondary-text); 
        text-transform: uppercase; 
        letter-spacing: 0.05em; 
    }
    .price-medium { 
        font-size: 20px; 
        font-weight: 700; 
        color: var(--primary-text); 
    }

    /* Screen Component */
    .screen-container { 
        perspective: 400px; 
        margin-bottom: 1rem; 
        text-align: center; 
    }
    .screen-arc { 
        height: 6px; 
        width: 80%; 
        margin: 0 auto; 
        background: var(--swift-blue); 
        border-radius: 50%; 
        box-shadow: 0 4px 15px rgba(0, 74, 173, 0.15); 
    }
    
    /* Seat Grid */
    .seat-grid-wrapper { 
        display: inline-block; 
        padding: 5px; 
        background: #fff; 
    }
    .seat-row { 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 6px; 
        margin-bottom: 6px; 
    }
    .seat {
        width: 32px; 
        height: 32px; 
        border-radius: 6px; 
        cursor: pointer;
        display: flex; 
        align-items: center; 
        justify-content: center;
        transition: all 0.1s; 
        border: 1.5px solid transparent;
    }

    /* Seat States */
    .seat.available {
        background-color: var(--seat-available);
        border: 1.5px solid var(--slate-300);
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.03);
    }
    .seat.available:hover {
        border-color: var(--swift-blue);
        transform: scale(1.05);
    }
    .seat.selected {
        background-color: var(--seat-selected) !important;
        border: 1.5px solid #D97706;
        box-shadow: 0 0 10px rgba(255,193,7,0.4);
    }
    .seat.taken {
        background-color: var(--seat-taken);
        border: 1.5px solid #94A3B8;
        cursor: not-allowed;
        opacity: 0.5;
    }
    .seat.taken::after { 
        content: '×'; 
        color: white; 
        font-size: 16px; 
        font-weight: bold; 
    }

    .row-label { 
        width: 24px; 
        font-size: 13px; 
        font-weight: 800; 
        color: #94A3B8; 
        text-align: center; 
    }
    
    /* Checkout Bar */
    .checkout-bar { 
        position: fixed; 
        bottom: 0; 
        left: 0; 
        right: 0; 
        background: white; 
        border-top: 1px solid #EDF2F7; 
        padding: 0.75rem 0; 
        z-index: 1000; 
    }
    .seat-pill-custom {
        background-color: #fff0c3 !important;
        border: 1.5px solid #D97706;
        color: #1E293B !important;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 74, 173, 0.2);
    }

    /* Scrollbar */
    .flex-grow-1::-webkit-scrollbar { width: 4px; }
    .flex-grow-1::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const navCountDisplay = document.getElementById('nav-count-display');
        const priceDisplay = document.getElementById('price-display');
        const seatsInput = document.getElementById('seats-input');
        const submitBtn = document.getElementById('submit-btn');
        const seatsList = document.getElementById('selected-seats-list');
        
        const availableSeats = document.querySelectorAll('.seat.available');
        const pricePerSeat = Number(@json($showtime->price ?? 0));
        let selected = [];

        availableSeats.forEach(seat => {
            seat.addEventListener('click', () => {
                const code = seat.dataset.code;
                
                if (selected.includes(code)) {
                    selected = selected.filter(c => c !== code);
                    seat.classList.remove('selected');
                } else {
                    selected.push(code);
                    seat.classList.add('selected');
                }

                const currentCount = selected.length;
                
                // Update Pills
                seatsList.innerHTML = selected.map(code => 
                    `<span class="seat-pill-custom">${code}</span>`
                ).join('');

                if (navCountDisplay) navCountDisplay.innerText = currentCount;

                // Update Price
                const total = currentCount * pricePerSeat;
                priceDisplay.innerText = total.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                
                // Form Update
                seatsInput.value = selected.join(',');
                submitBtn.disabled = (currentCount === 0);
            });
        });
    });
</script>
@endsection