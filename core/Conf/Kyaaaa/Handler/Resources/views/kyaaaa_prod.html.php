<style>
    *{
        transition: all 0.6s;
    }

    html {
        height: 100%;
    }

    body{
        font-family: 'Courier New', monospace;
        color: #abaebb;
        margin: 0;
        background:#2a2f34;
    }

    #main{
        display: table;
        width: 100%;
        height: 100vh;
        text-align: center;
    }

    .fof{
        display: table-cell;
        vertical-align: middle;
    }

    .fof h1{
        font-size: 25px;
        display: inline-block;
        padding-right: 12px;
        animation: type .5s alternate infinite;
        margin-left:10px;
        margin-right:10px;
    }

    @keyframes type{
        from{box-shadow: inset -3px 0px 0px #888;}
        to{box-shadow: inset -3px 0px 0px transparent;}
    }
</style>
<div id="main">
    <div class="fof">
        <h1>Kyaaaa~ Something Went Wrong!</h1>
    </div>
</div>
<script>
    document.title = 'Kyaaaa~ Et-dah!'
</script>