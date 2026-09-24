<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    @section('title', __('Akatsuki - Catégories'))
    <main class="container py-5">
        <section class="categories-page">

            <div class="container">

                <!-- En-tête -->
                <div class="categories-header text-center">
                    <span class="categories-subtitle">AKATSUKI SHOP</span>

                    <h1>Nos catégories</h1>

                    <p>
                        Découvrez toutes nos catégories et trouvez facilement
                        les produits qui vous correspondent.
                    </p>
                </div>


                <!-- Catégories -->
                <div class="row g-4">

                    <!-- Mode -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <a href="produits.html?cat=Mode" class="category-box category-mode">

                            <img src="assets/img/mode.png" alt="Mode">

                            <div class="category-overlay">
                                <span>01</span>
                                <h2>Mode</h2>
                                <p>Vêtements & accessoires</p>

                                <div class="category-link">
                                    Découvrir <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>

                        </a>
                    </div>


                    <!-- Tech -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <a href="produits.html?cat=Tech" class="category-box category-tech">

                            <img src="assets/img/tech.png" alt="Tech">

                            <div class="category-overlay">
                                <span>02</span>
                                <h2>Tech</h2>
                                <p>Gadgets & accessoires</p>

                                <div class="category-link">
                                    Découvrir <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>

                        </a>
                    </div>


                    <!-- Maison -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <a href="produits.html?cat=Maison" class="category-box category-maison">

                            <img src="assets/img/mais.png" alt="Maison">

                            <div class="category-overlay">
                                <span>03</span>
                                <h2>Maison</h2>
                                <p>Confort & décoration</p>

                                <div class="category-link">
                                    Découvrir <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>

                        </a>
                    </div>


                    <!-- Beauté -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <a href="produits.html?cat=Beauté" class="category-box category-beaute">

                            <img src="assets/img/vet.png" alt="Beauté">

                            <div class="category-overlay">
                                <span>04</span>
                                <h2>Beauté</h2>
                                <p>Soins & bien-être</p>

                                <div class="category-link">
                                    Découvrir <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>

                        </a>
                    </div>


                    <!-- Sport -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <a href="produits.html?cat=Sport" class="category-box">

                            <img src="assets/img/sport.png" alt="Sport">

                            <div class="category-overlay">
                                <span>05</span>
                                <h2>Sport</h2>
                                <p>Équipements & vêtements</p>

                                <div class="category-link">
                                    Découvrir <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>

                        </a>
                    </div>


                    <!-- Chaussures -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <a href="produits.html?cat=Chaussures" class="category-box">

                            <img src="assets/img/chaussures.png" alt="Chaussures">

                            <div class="category-overlay">
                                <span>06</span>
                                <h2>Chaussures</h2>
                                <p>Hommes & femmes</p>

                                <div class="category-link">
                                    Découvrir <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>

                        </a>
                    </div>


                    <!-- Accessoires -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <a href="produits.html?cat=Accessoires" class="category-box">

                            <img src="assets/img/accessoires.png" alt="Accessoires">

                            <div class="category-overlay">
                                <span>07</span>
                                <h2>Accessoires</h2>
                                <p>Sacs, montres & bijoux</p>

                                <div class="category-link">
                                    Découvrir <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>

                        </a>
                    </div>


                    <!-- Alimentation -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <a href="produits.html?cat=Alimentation" class="category-box">

                            <img src="assets/img/alimentation.png" alt="Alimentation">

                            <div class="category-overlay">
                                <span>08</span>
                                <h2>Alimentation</h2>
                                <p>Produits alimentaires</p>

                                <div class="category-link">
                                    Découvrir <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>

                        </a>
                    </div>

                </div>

            </div>

        </section>
    </main>
</div>