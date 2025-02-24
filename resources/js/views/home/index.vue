<template>
    <main id="mainContent" class="container-fluid pr-0">
        <div class="banner-background d-flex col-12">
            <div class="banner-filter"></div>
            <div class="container h-100 d-flex align-items-center">
                <div class="row w-100 d-flex justify-content-center align-items-end">
                    <div class="col-12 col-md-6 d-flex flex-column justify-content-center align-items-start">
                        <h1 class="banner-title">NEW ROULETTE GAME !</h1>
                        <p class="banner-text">You can now play roulette at Royal Flush Casino</p>
                        <Button class="banner-button" buttonColor="yellow" buttonStyle="filled">Click here to be a winner!!</Button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container games-container">
            <div class="row d-flex justify-content-center">
                <div class="col-11 container-category-title d-flex justify-content-between align-items-center">
                    <h5 class="category-title">FEATURED GAMES</h5>
                    <div class="nav-arrows-container">
                        <button class="nav-arrow" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="nav-arrow" disabled>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="row d-flex justify-content-around mt-3">
                    <a href="#" class="col-2 game-card">
                        <img src="/images/bingoGame.webp" alt="Bingo" class="game-image"/>
                        <p class="game-title">BINGO</p>
                        <div class="bottom-game-line"></div>
                    </a>
                    <a href="#" class="col-2 game-card">
                        <img src="/images/rouletteGame.webp" alt="Roulette" class="game-image"/>
                        <p class="game-title">ROULETTE</p>
                        <div class="bottom-game-line"></div>
                    </a>
                    <a href="#" class="col-2 game-card">
                        <img src="/images/pokerGame.webp" alt="Poker" class="game-image"/>
                        <p class="game-title">POKER</p>
                        <div class="bottom-game-line"></div>
                    </a>
                    <a href="#" class="col-2 game-card">
                        <img src="/images/blackjackGame.webp" alt="Blackjack" class="game-image"/>
                        <p class="game-title">BLACKJACK</p>
                        <div class="bottom-game-line"></div>
                    </a>
                    <div href="#" class="col-2 game-card"></div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-11 container-category-title d-flex justify-content-between align-items-center">
                    <h5 class="category-title">NEW CASINO GAMES</h5>
                    <div class="nav-arrows-container">
                        <button class="nav-arrow" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="nav-arrow" disabled>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="row d-flex justify-content-around mt-3">
                    <a href="#" class="col-2 game-card">
                        <img src="/images/bingoGame.webp" alt="Bingo" class="game-image"/>
                        <p class="game-title">BINGO</p>
                        <div class="bottom-game-line"></div>
                    </a>
                    <a href="#" class="col-2 game-card">
                        <img src="/images/rouletteGame.webp" alt="Roulette" class="game-image"/>
                        <p class="game-title">ROULETTE</p>
                        <div class="bottom-game-line"></div>
                    </a>
                    <a href="#" class="col-2 game-card">
                        <img src="/images/pokerGame.webp" alt="Poker" class="game-image"/>
                        <p class="game-title">POKER</p>
                        <div class="bottom-game-line"></div>
                    </a>
                    <a href="#" class="col-2 game-card">
                        <img src="/images/blackjackGame.webp" alt="Blackjack" class="game-image"/>
                        <p class="game-title">BLACKJACK</p>
                        <div class="bottom-game-line"></div>
                    </a>
                    <div href="#" class="col-2 game-card"></div>
                </div>
            </div>
        </div>
    </main>
</template>

<script setup>
import Button from '../../components/Button.vue';
</script>

<style scoped>
    #mainContent {
        background-color: #2A2A2A;
    }
    .banner-background {
        height: 300px;
        background-image: url('/images/banner.jpg');
        background-size: cover;
        background-position: center;
        position: relative;
        margin-bottom: 75px;
    }
    .banner-filter {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Ajusta la opacidad según sea necesario */
        z-index: 1;
    }
    .banner-title {
        font-weight: bold;
        color: red;
        font-size: 48px;
        z-index: 2;
        width: 75%;
    }
    .banner-text {
        font-size: 24px;
        font-weight: bolder;
        color: white;
        z-index: 2;
        width: 60%;
    }
    .banner-button {
        padding: 10px 20px;
        font-size: 20px;
        cursor: pointer;
        z-index: 2;
        width: 60%;
    }
    .container-category-title {
        border-left: 5px solid red;
        margin-top: 50px;
        background: rgb(0,0,0);
        background: linear-gradient(90deg, #000000 16%, #2a2a2a 40%);
    }
    .category-title{
        margin: 0;
        color: red;
        font-size: 18px;
        display: flex;
        align-items: center;
    }
    .game-card {
        height: 200px;
        width: 150px;
        text-align: center;
        margin: 10px;
    }
    .game-image {
        width: 100%;
        object-fit: cover;
        border-radius: 25%;
        border: 1px solid #000000;
    }
    .game-title {
        margin-top: 10px;
        font-size: 16px;
        color: white;
    }
    .bottom-game-line{
        width: 100%;
        height: 1px;
        background: rgb(0,0,0);
        background: linear-gradient(90deg, #00000000 0%, #ff0000 50%, #00000000 100%);
    }
    .background-none{
        background: none;
        border: none;
    }
    .nav-arrows-container{
        display: flex;
        gap: 5px;
    }
    .nav-arrow:enabled{
        background: none;
        border: 1px solid #717171;
        background-color: #000000;
        border-radius: 100%;
        height: 30px;
        width: 30px;
        color: #717171;
        font-size: 16px;
    }
    .nav-arrow:disabled{
        background: none;
        border: 1px solid #717171;
        background-color: #212121;
        border-radius: 100%;
        height: 30px;
        width: 30px;
        color: #717171;
        font-size: 16px;
    }
</style>