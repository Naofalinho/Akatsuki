<footer class="footer">
    <div class="container">
        <div class="row g-4">

            {{-- Présentation --}}
            <div class="col-md-5">
                <h3>AKATSUKI</h3>

                <p>
                    Boutique e-commerce moderne et responsive.
                </p>
            </div>

            {{-- Liens boutique --}}
            <div class="col-md-3">
                <h6>Boutique</h6>

                <a href="{{ route('catalogue') }}">
                    Produits
                </a>

                <a href="{{ route('favoris') }}">
                    Favoris
                </a>

                <a href="{{ route('panier') }}">
                    Panier
                </a>
            </div>

            {{-- Newsletter --}}
            <div class="col-md-4">
                <h6>Newsletter</h6>

                <div class="input-group">
                    <input
                        type="email"
                        class="form-control"
                        placeholder="Votre email"
                    >

                    <button
                        type="button"
                        class="btn btn-light"
                    >
                        S'inscrire
                    </button>
                </div>
            </div>

        </div>

        <hr>

        <small>
            © 2026 AKATSUKI
        </small>
    </div>
</footer>