<div>
    <a
        href="{{ route('panier') }}"
        class="account-btn position-relative"
        title="Panier"
    >
        <i class="bi bi-cart4"></i>

        <span>
            Panier
        </span>

        @if ($count > 0)
            <span class="cart-count">
                {{ $count }}
            </span>
        @endif
    </a>
</div>