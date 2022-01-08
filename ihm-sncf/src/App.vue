<template>
 <header>
    <h1><router-link router-link to="/">Kelcarte?</router-link></h1>
</header>
<main>
  <router-view />
</main>
</template>

<style>
* {
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  padding: 0;
  margin: 0;
  font-family: Roboto, 'Sans-Serif';
}
::selection
{
  background: #0085c2;
  color: white;
}
:invalid, :focus
{
  outline: none;
  box-shadow: none;
}

.center {
    text-align: center;
}

body {
    background: url('assets/trainstation.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: "";
}

header {
    position: absolute;
    top: 0; left: 0; right: 0;
    background: #fff;
    height: 80px;
    border-bottom: 1px solid #dedede;
}

header, main {
    padding: 0 10%;
}

header h1 {
    line-height: 80px;
    vertical-align: middle;
    font-weight: 300;
    font-size: 2em;
    text-align: center;
    transform: rotate(-3deg);
}

header h1 a, header h1 a:visited {
    color: white;
    background: #3B3B98;
    padding: 3px 5px;
    border-radius: 5px;
    text-decoration: none;
}

main {
    margin-top: 150px;
}

main article {
    padding: 4%;
    width: 80%;
    margin: 0 auto;
    background: white;
    border-radius: 5px;
    position: relative;
}

.breadcrumb ul {
    display: flex;
    list-style-type: none;
    justify-content: center;
    margin-bottom: 30px;
}

.breadcrumb li:not(:first-child):before {
    content: "→";
    margin: 0 10px;
    font-size: 24px;
}

.breadcrumb li:first-child::before {
    content: "";
    font-size: 24px;
}

.breadcrumb li a, .breadcrumb a:visited {
    color: black;
    text-decoration: none;
}

.breadcrumb li a:hover {
    text-decoration: underline;
}

body .active, a .step:hover, .breadcrumb li:hover a .step {
    background: #3B3B98;
    border: none;
    color: white;
}
body .stepTextActive {
    color: #30336b;
}

.step {
    width: 24px; height: 24px;
    line-height: 24px;
    vertical-align: middle;
    display: inline-block;
    text-align: center;
    color: #333;
    border: 1px solid #333;
    border-radius: 20px;
    font-weight: bold;
    font-size: 15px;
}

#step0 {
    height: 350px;
    background: url('assets/pub.jpg') no-repeat top;
    background-color: #fcf7fb;
    background-size: 100%;
}

#step0 button {
  position: relative;
  top: 190px;
}

#step2 input {
    width: 60px;
    padding: inherit;
    border: none;
    border-bottom: 2px solid purple;
    font-size: 16px;
    background: #eee;
    padding: 3px;
}

article h1 {
    font-size: 20px;
    position: absolute;
    top: -20px; left: -20px;
    transform: rotate(-2deg);
    background: black;
    color: white;
    padding: 10px 5px;
    border-radius: 5px;
}

article .min {
    margin-top: 10px;
    font-size: 14px;
    color: #555;
}

article p {
    font-size: 16px;
}

input[name="depart"], select {
    width: 200px; height: 40px;
    border: none;
    border-bottom: 2px solid purple;
    font-size: 16px;
    background: #eee;
}

input {
    padding: 0 20px;
}

article button {
    background-color: #800080;
    min-width: 240px; height: 60px;
    margin: 30px auto 0 auto;
    border: none;
    color: white;
    font-size: 17px;
    border: 3px solid transparent;
    border-radius: 30px;
}
article button:hover {
    border: 3px solid #800080;
    background: white;
    color: #800080;
    cursor: pointer;
    transition: all 200ms linear;
}

#rotatePhone {
    display: none;
}

.green {
    color: green;
}
.red {
    color: red;
}

@media screen and (max-width: 1366px) {
    .hide {
        display: none;
    }
    .step {
        width: 32px; height: 32px;
        line-height: 32px;
        font-size: 18px;
    }
}

@media screen and (max-width: 700px) {
    body .step {
        width: 20px; height: 20px;
        line-height: 20px;
        font-size: 13px;
    }
    .breadcrumb {
        margin: 20px auto;
    }
    .breadcrumb li::before {
        font-size: 16px;
    }
    article h1 {
        font-size: 10px;
        margin-bottom: 30px;
    }
    #step0 {
        height: auto;
        background-image: none;
    }
    #step0 button {
        top: inherit;
    }
    article button {
        min-width: inherit;
        width: 80%;
        font-size: 15px;
    }
    .smallHide {
        display: none;
    }
    .step {
        width: 32px; height: 32px;
        line-height: 32px;
        font-size: 18px;
    }
    #step1 input[type="text"], .step1 select, article button {
        width: 95%;
    }
    #step1 input[type="text"], #step1 select, #step3 select {
        margin-top: 20px;
    }
}
@media screen and (max-height: 700px) and (orientation: portrait) {
    #resultData {
        display: none;
    }
    #rotatePhone {
        display: block;
        width: 100%;
    }
}

@media screen (max-height: 700px) and (orientation: landscape) {
    #resultData {
        display: block;
    }
    #rotatePhone {
        display: none;
    }
}

</style>