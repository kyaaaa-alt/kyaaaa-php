<?php
/**
* Layout template file for Whoops's pretty error output.
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kyaaaa~ Et-dah!</title>
    <meta name="robots" content="noindex,nofollow"/>
    <style><?php echo $stylesheet ?></style>
    <style><?php echo $prismCss ?></style>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
        .nav-link.active {
            background-color: #212529 !important;
            color: #fff !important;
            border-color: #212529 !important;
        }
        .nav-link:hover {
            background-color: #212529;
            color: #fff !important;
            border-color: #212529 !important;
        }
        .nav-tabs {
            --bs-nav-tabs-border-color: #212529 !important;
        }
    </style>

  </head>
<body style="background-color:#6c757d" class="font-monospace">
<main>
    <div class="container py-4">
        <div class="row align-items-md-stretch">

            <div class="col-md-12 mb-3">
                <div class="h-100 p-5 text-bg-dark rounded-3">
                    <h6 class="text-warning letter fw-normal">
                        <span id="errtitle">
                        <?php foreach ($name as $i => $nameSection): ?>
                            <?php if ($i == count($name) - 1): ?>
                                <?php echo 'Kyaaaa~ㅤ' . $tpl->escape($nameSection) ?>
                            <?php else: ?>
                                <?php echo $tpl->escape($nameSection) . '\\';?>
                            <?php endif ?>
                        <?php endforeach ?>
                        </span>
                        <?php if ($code): ?>
                            <span title="Exception Code">(<?php echo $tpl->escape($code)?>)</span>
                        <?php endif ?>
                    </h6>
                    <h5 class="d-inline">
                        <?php if (!empty($message)): ?>
                            <span><?php echo $tpl->escape($message) ?></span>
                            <?php if (count($previousMessages)): ?>
                                <div class="exc-title prev-exc-title">
                                    <span class="exc-title-secondary">Previous exceptions</span>
                                </div>
                                <ul>
                                    <?php foreach ($previousMessages as $i => $previousMessage): ?>
                                        <li>
                                            <?php echo $tpl->escape($previousMessage) ?>
                                            <span class="prev-exc-code">(<?php echo $previousCodes[$i] ?>)</span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif ?>
                        <?php else: ?>
                            <span class="exc-message-empty-notice">No message</span>
                        <?php endif ?>
                    </h5>
                    <a target="_blank" href="https://google.com/search?q=<?php echo urlencode(implode('\\', $name).' '.$message) ?>" class="text-decoration-none">
                        <h5 class="d-inline">
                            <svg fill="#83e0ff" class="" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 72 72" width="26px" height="26px"><path d="M 43 12 C 40.791 12 39 13.791 39 16 C 39 18.209 40.791 20 43 20 L 46.34375 20 L 35.171875 31.171875 C 33.609875 32.733875 33.609875 35.266125 35.171875 36.828125 C 35.951875 37.608125 36.977 38 38 38 C 39.023 38 40.048125 37.608125 40.828125 36.828125 L 52 25.65625 L 52 29 C 52 31.209 53.791 33 56 33 C 58.209 33 60 31.209 60 29 L 60 16 C 60 13.791 58.209 12 56 12 L 43 12 z M 23 14 C 18.037 14 14 18.038 14 23 L 14 49 C 14 53.962 18.037 58 23 58 L 49 58 C 53.963 58 58 53.962 58 49 L 58 41 C 58 38.791 56.209 37 54 37 C 51.791 37 50 38.791 50 41 L 50 49 C 50 49.551 49.552 50 49 50 L 23 50 C 22.448 50 22 49.551 22 49 L 22 23 C 22 22.449 22.448 22 23 22 L 31 22 C 33.209 22 35 20.209 35 18 C 35 15.791 33.209 14 31 14 L 23 14 z"/><span class="fw-normal font-size" style="color:#83e0ff;"><small>Search</small></span></svg>
                        </h5>
                    </a>
                </div>
            </div>

            <div class="col-md-12">
                <div class="h-100 rounded-3">
                    <div class="frame-code-container <?php echo (!$has_frames ? 'empty' : '') ?>">
                        <?php $chkloop = 0; foreach ($frames as $i => $frame):
                            if (++$chkloop == 2) break;
                            ?>
                            <?php $line = $frame->getLine(); ?>
                            <div class="frame-code <?php echo ($i == 0 ) ? 'active' : '' ?>" id="frame-code-<?php echo $i ?>">
                                <div class="frame-file">
                                    <?php $filePath = $frame->getFile(); ?>
                                    <?php if ($filePath && $editorHref = $handler->getEditorHref($filePath, (int) $line)): ?>
                                        <a href="<?php echo $editorHref ?>" class="editor-link"<?php echo ($handler->getEditorAjax($filePath, (int) $line) ? ' data-ajax' : '') ?>>
                                            Open:
                                            <strong><?php echo $tpl->breakOnDelimiter('/', $tpl->escape($filePath ?: '<#unknown>')) ?></strong>
                                        </a>
                                    <?php else: ?>
                                        <div class="alert alert-dark py-2 rounded-3 border-0 text-white fw-normal" style="margin-bottom: -20px;background: #2d2d2d;"><?php echo $tpl->breakOnDelimiter('/', $tpl->escape($filePath ?: '<#unknown>')) ?></div>

                                    <?php endif ?>
                                </div>
                                <?php
                                // Do nothing if there's no line to work off
                                if ($line !== null):

                                    // the $line is 1-indexed, we nab -1 where needed to account for this
                                    $range = $frame->getFileLines($line - 5, 10);

                                    // getFileLines can return null if there is no source code
                                    if ($range):
                                        $range = array_map(function ($line) { return empty($line) ? ' ' : $line;}, $range);
                                        $start = key($range) + 1;
                                        $code  = join("\n", $range);
                                        ?>
                                        <pre class="line-numbers rounded-3"
                                             data-line="<?php echo $line ?>"
                                             data-start="<?php echo $start ?>"
                                        ><code class="language-php"><?php echo $tpl->escape($code) ?></code></pre>

                                    <?php endif ?>
                                <?php endif ?>

                                <?php $frameArgs = $tpl->dumpArgs($frame); ?>
                                <?php if ($frameArgs): ?>
                                    <div class="frame-file">
                                        Arguments
                                    </div>
                                    <div id="frame-code-args-<?=$i?>" class="code-block frame-args">
                                        <?php echo $frameArgs; ?>
                                    </div>
                                <?php endif ?>

                                <?php
                                // Append comments for this frame
                                $comments = $frame->getComments();
                                ?>
                                <div class="frame-comments <?php echo empty($comments) ? 'empty' : '' ?>">
                                    <?php foreach ($comments as $commentNo => $comment): ?>
                                        <?php extract($comment) ?>
                                        <div class="frame-comment" id="comment-<?php echo $i . '-' . $commentNo ?>">
                                            <span class="frame-comment-context"><?php echo $tpl->escape($context) ?></span>
                                            <?php echo $tpl->escapeButPreserveUris($comment) ?>
                                        </div>
                                    <?php endforeach ?>
                                </div>

                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" style="color:#000;" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Server</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" style="color:#000;" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Request</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" style="color:#000;" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Cookies & Session</button>
                    </li>

                </ul>
                <div class="tab-content px-3 py-2 bg-dark rounded-3" style="margin-top:-6px;" id="myTabContent">
                    <div class="tab-pane fade text-white show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                        <div class="data-table-container" id="data-tables">
                            <?php foreach ($tables['Server/Request Data'] as $label => $data): ?>
                                <div class="data-table" id="sg">
                                    <table class="data-table">
                                        <thead>
                                        <tr>
                                            <td class="data-table-k">Key</td>
                                            <td class="data-table-v">Value</td>
                                        </tr>
                                        </thead>
                                            <tr>
                                                <td><?php echo $tpl->escape($label) ?></td>
                                                <td><?php echo $tpl->dump($data) ?></td>
                                            </tr>
                                    </table>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <div class="tab-pane fade text-white" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                        <?php
                        if (!empty($tables['GET Data'])) {
                            echo '<h6>GET</h6>';
                            foreach ($tables['GET Data'] as $label => $data) {
                                ?>
                                <div class="data-table" id="sg">
                                    <table class="data-table">
                                        <thead>
                                        <tr>
                                            <td class="data-table-k">Key</td>
                                            <td class="data-table-v">Value</td>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td><?php echo $tpl->escape($label) ?></td>
                                            <td><?php echo $tpl->dump($data) ?></td>
                                        </tr>
                                    </table>
                                </div>
                            <?php } } else {
                            echo '<h6>GET</h6>';
                            echo '<p class="text-muted">EMPTY</p>';
                            } ?>

                        <?php
                        if (!empty($tables['POST Data'])) {
                            echo '<h6>POST</h6>';
                        foreach ($tables['POST Data'] as $label => $data) {
                            ?>
                            <div class="data-table" id="sg">
                                <table class="data-table">
                                    <thead>
                                    <tr>
                                        <td class="data-table-k">Key</td>
                                        <td class="data-table-v">Value</td>
                                    </tr>
                                    </thead>
                                    <tr>
                                        <td><?php echo $tpl->escape($label) ?></td>
                                        <td><?php echo $tpl->dump($data) ?></td>
                                    </tr>
                                </table>
                            </div>
                        <?php } } else {
                            echo '<h6>POST</h6>';
                            echo '<p class="text-muted">EMPTY</p>';
                        } ?>

                        <?php
                        if (!empty($tables['Files'])) {
                            echo '<h6>FILES</h6>';
                            foreach ($tables['Files'] as $label => $data) {
                                ?>
                                <div class="data-table" id="sg">
                                    <table class="data-table">
                                        <thead>
                                        <tr>
                                            <td class="data-table-k">Key</td>
                                            <td class="data-table-v">Value</td>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td><?php echo $tpl->escape($label) ?></td>
                                            <td><?php echo $tpl->dump($data) ?></td>
                                        </tr>
                                    </table>
                                </div>
                            <?php } } else {
                            echo '<h6>FILES</h6>';
                            echo '<p class="text-muted">EMPTY</p>';
                        } ?>

                    </div>
                    <div class="tab-pane fade text-white" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                        <?php
                        if (!empty($tables['Cookies'])) {
                            echo '<h6>COOKIES</h6>';
                            foreach ($tables['Cookies'] as $label => $data) {
                                ?>
                                <div class="data-table" id="sg">
                                    <table class="data-table">
                                        <thead>
                                        <tr>
                                            <td class="data-table-k">Key</td>
                                            <td class="data-table-v">Value</td>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td><?php echo $tpl->escape($label) ?></td>
                                            <td><?php echo $tpl->dump($data) ?></td>
                                        </tr>
                                    </table>
                                </div>
                            <?php } } else {
                            echo '<h6>COOKIES</h6>';
                            echo '<p class="text-muted">EMPTY</p>';
                        } ?>

                        <?php
                        if (!empty($tables['Session'])) {
                            echo '<h6>SESSION</h6>';
                            foreach ($tables['Session'] as $label => $data) {
                                ?>
                                <div class="data-table" id="sg">
                                    <table class="data-table">
                                        <thead>
                                        <tr>
                                            <td class="data-table-k">Key</td>
                                            <td class="data-table-v">Value</td>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td><?php echo $tpl->escape($label) ?></td>
                                            <td><?php echo $tpl->dump($data) ?></td>
                                        </tr>
                                    </table>
                                </div>
                            <?php } } else {
                            echo '<h6>SESSION</h6>';
                            echo '<p class="text-muted">EMPTY</p>';
                        } ?>
                    </div>
                </div>

            </div>
        </div>
</main>


<script><?php echo $prismJs ?></script>
<script><?php echo $zepto ?></script>
<script><?php echo $javascript ?></script>
<script>
    const errTitle = document.getElementById('errtitle').innerHTML
    const tmpTitle = errTitle.replace(/\s/g, '');
    console.log(tmpTitle)
    document.getElementById('errtitle').innerHTML = tmpTitle.replace(String.raw`\Kyaaaa~ㅤ`, "\\");
</script>
</body>
</html>
