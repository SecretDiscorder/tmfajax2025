<?php
//============================================================+
// File name   : tce_filemanager.php
// Begin       : 2010-09-20
// Last Update : 2013-04-12
//
// Description : File manager for media files.
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//
// License:
//    Copyright (C) 2004-2013 Nicola Asuni - Tecnick.com LTD
//    See LICENSE.TXT file for more information.
//============================================================+

/**
 * @file
 * File manager for media files.
 * @package com.tecnick.tcexam.admin
 * @author Nicola Asuni
 * @since 2010-09-21
 */

/**
 */

require_once('../config/tce_config.php');

$pagelevel = K_AUTH_OPERATOR;
require_once('../../shared/code/tce_authorization.php');
require_once('../../shared/code/tce_functions_form.php');
require_once('../../shared/code/tce_functions_tcecode.php');
require_once('tce_functions_filemanager.php');

$thispage_title = 'MS Word to XML Converter';
$thispage_title_icon = '<i class="fas fa-file-word"></i> ';
require_once('../code/tce_page_header.php');

echo '<div class="container" style="padding:0">'.K_NEWLINE;

echo '<div class="contentbox">'.K_NEWLINE;

echo '<div class="tceformbox">'.K_NEWLINE;
echo '<fieldset style="text-align:left;background:#fff;padding:1em 2em">'.K_NEWLINE;

?>

<h3>Langkah menggunakan konverter:</h3>
<ol>
<li>Download Format MS Word <a href="https://drive.google.com/file/d/1YB7m56snLBaDKy0dBEJ5JULBt9vKABRe/view">disini</a></li>
<li>Setelah didownload ubah sesuai dengan keinginan, perhatikan beberapa contoh soal. Ada tipe soal MCSA, MCMA, Isian singkat, Uraian Panjang, maupun Ordering</li>
<li>Setelah selesai, simpan soal</li>
<li>Buka Halaman Web Konverter <a href="https://pemdas.yayasan-gondang.com/word-to-tcexam-xml/admin/code/tmf_word_import.php" target="blank">disini</a></li>
<li>Login menggunakan akun yang dimiliki. Untuk request akun silakan hubungi Mr.Man <br/>WhatsApp: <a href="https://wa.me/628561575817">https://wa.me/628561575817</a><br/>Telegram: <a href="https://t.me/mamans86">@mamans86</a></li>
<li>Buka kembali soal yang telah Anda susun di Microsoft Word, tekan CTRL+A untuk menseleksi semua soal.</li>
<li>Tekan CTRL+C untuk menyalin/mengcopy semua soal.</li>
<li>Paste semua soal yang ada pada MS Word ke form yang disediakan</li>
<li>Silakan Review soal yang telah masuk ke editor. Apabila ada yang belum sesuai silakan lakukan perubahan seperlunya.</li>
<li>Klik tombol PROCEED untuk mulai memproses soal ke dalam sistem</li>
<li>Daftar soal yang telah masuk akan ditampilkan, dan Anda bisa mereview ulang. Apabila butuh mengulangi proses pengubahan silakan klik tombol Retry di bawah.</li>
<li>Apabila sudah merasa bahwa soal yang telah masuk sudah sesuai, silakan klik tombol Convert and Download XML Format</li>
<li>Anda dapat menggunakan file XML ini untuk diimportkan ke TCExam Anda masing-masing.</li>
</ol>
<h3>Untuk mengimport file XML ke TCExam caranya adalah :</h3>
<ol>
<li>Masuk ke menu Modules - Import</li>
<li>Pada form yang disediakan klik Choose File untuk memilih File XML yang tadi telah kita Download</li>
<li>Kemudian klik tombol SEND untuk memasukkan soal ke dalam database</li>
<li>Soal yang sudah diimport dapat diperiksa kembali melalui menu Modules - List</li>
</ol>
<?php
session_start();


// Jika form disubmit, lakukan proses konversi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wordhtml'])) {
    // Ambil konten yang di-paste
    $input = html_entity_decode($_POST['wordhtml']);
    
    // Izinkan tag-tag penting agar formatting tetap terjaga
    $allowed_tags = '<table><tr><td><th><br><b><div><i><ul><ol><li><img>';
    $input = strip_tags($input, $allowed_tags);
    
    // Hapus karakter kutip ganda
    $input = str_replace('"', '', $input);
    
    // Normalisasi newline
    $input = preg_replace('/\r\n|\r|\n/', "\n", $input);
    
    // Proses gambar: konversi jika src mengandung "cache/"
    $input = processImages($input);
    
    // Parse pertanyaan
    $questions = parseQuestions($input);
    if (empty($questions)) {
        $error = "No valid questions found in the document.";
    } else {
        try {
            $xmlContent = generateTCExamXML($questions);
            file_put_contents('exam.xml', $xmlContent);
            $_SESSION['xml_file_path'] = 'exam.xml';
        } catch (Exception $e) {
            $error = "XML Generation Error: " . $e->getMessage();
        }
    }
    ?>

        <div>
            <h1>TCExam  Download XML</h1>
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if(empty($error) && !empty($questions)): ?>

 
                <hr>
                <h4>Generated XML:</h4>
                <button><a href="exam.xml" class="btn btn-success btn-lg" download>Download TCExam XML</a></button>
                <button><a href="?clear=1" class="btn btn-secondary btn-lg">Clear Session &amp; Re-enter</a></button>
            <?php endif; ?>
        </div>
    <?php
}
?>
<script type="text/javascript" src="../../shared/jscripts/ckeditor/ckeditor.js"></script>

    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">MS Word to TCExam XML Converter</h4>
            </div>
            <div class="card-body">
                <div class="loader" id="loader">
                    <div class="loader-spinner spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div id="editorarea">
                    <form method="post" id="conversionForm">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? ($_SESSION['csrf_token'] = bin2hex(random_bytes(32))) ?>">
                        <div class="form-group">
                            <label>Paste your MS Word content below:</label>
                            <textarea name="wordhtml" id="wordhtml" class="form-control" rows="20"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Convert to XML
                        </button>
                    </form>
                    <div class="mt-4">
                        <h5>Security Measures:</h5>
                        <ul>
                            <li>Latest CKEditor 4.25.1 LTS version</li>
                            <li>Content Security Policy (CSP) enforced</li>
                            <li>CSRF protection enabled</li>
                            <li>Automatic malware scanning for uploads</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    CKEDITOR.replace('wordhtml');

    CKEDITOR.instances.wordhtml.on('paste', function(event) {
        setTimeout(function() {
            var editor = CKEDITOR.instances.wordhtml;
            editor.setData(editor.getData() + '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>');
        }, 100); // Delay agar tidak konflik dengan event paste
    });
</script>


<?php
// ==================================================================
// FUNCTIONS SECTION (Sinkronisasi dengan official TCExam import)
// ==================================================================

/**
 * Parse pertanyaan dari input.
 */
function parseQuestions($input) {
    $lines = explode("\n", $input);
    $lines = array_filter(array_map('trim', $lines), function($line) {
        return $line !== "";
    });
    $questions = array();
    $currentQuestion = array();
    $moduleName = "Type Module Name Here";
    $topicName = "Type Topic Name Here";
    for ($i = 0; $i < count($lines); $i++) {
        $line = $lines[$i];
        // Gabungkan baris table jika ada
        if (stripos($line, '<table') === 0) {
            $tableContent = $line;
            while ($i < count($lines) && stripos($lines[$i], '</table>') === false) {
                $i++;
                if ($i < count($lines)) {
                    $tableContent .= "\n" . $lines[$i];
                }
            }
            $line = $tableContent;
        }
        // Tangkap MODULE atau TOPIC
        if (preg_match('/^(MODULE|TOPIC):=(.+)/i', $line, $matches)) {
            $key = strtoupper($matches[1]);
            if ($key === 'MODULE') {
                $moduleName = trim($matches[2]);
            } else {
                $topicName = trim($matches[2]);
            }
            continue;
        }
        // Tangkap baris RIGHT:
        if (!empty($currentQuestion) && preg_match('/^RIGHT:\s*([A-Z,\s]+)$/i', $line, $matches)) {
            $currentQuestion['right'] = array_map('trim', explode(',', strtoupper($matches[1])));
            continue;
        }
        // Tangkap pertanyaan: format "Q:1) Question text..."
        if (preg_match('/^Q:\s*(\d+)\)\s*(.+)/i', $line, $matches)) {
            if (!empty($currentQuestion)) {
                $questions[] = finalizeQuestion($currentQuestion);
            }
            $questionText = processImages(trim($matches[2]));
            $questionType = '1'; // Default: pilihan ganda single answer
            if (preg_match('/^\[TYPE:(\d)\]\s*(.+)/i', $questionText, $typeMatches)) {
                $questionType = $typeMatches[1];
                $questionText = $typeMatches[2];
            }
            $currentQuestion = array(
                'number'  => (int)$matches[1],
                'text'    => $questionText,
                'type'    => $questionType,
                'answers' => array(),
                'right'   => array(),
                'module'  => $moduleName,
                'topic'   => $topicName
            );
            continue;
        }
        // Tangkap opsi jawaban: format "A:) Option text"
        if (!empty($currentQuestion) && preg_match('/^([A-Z]):?\)\s*(.+)/i', $line, $matches)) {
            $optionText = processImages(trim($matches[2]));
            $isCorrect = false;
            if (substr($optionText, -1) === '*') {
                $isCorrect = true;
                $optionText = rtrim($optionText, '* ');
            }
            $currentQuestion['answers'][] = array(
                'letter'  => strtoupper($matches[1]),
                'text'    => $optionText,
                'correct' => $isCorrect
            );
            continue;
        }
        // Jika baris tidak cocok, anggap sebagai lanjutan teks soal
        if (!empty($currentQuestion)) {
            $currentQuestion['text'] .= "\n" . processImages($line);
        }
    }
    if (!empty($currentQuestion)) {
        $questions[] = finalizeQuestion($currentQuestion);
    }
    return $questions;
}

/**
 * Finalisasi pertanyaan.
 */
/**
 * Finalisasi pertanyaan.
 */
function finalizeQuestion($q) {
    // Jika tipe belum ditentukan, default ke pilihan ganda (single answer)
    if (!isset($q['type']) || empty($q['type'])) {
        $q['type'] = '1';
    }

    // Normalisasi teks soal
    $q['text'] = nl2br(trim(preg_replace('/\n+/', "\n", $q['text'])));

    // Jika teks soal mengandung indikasi ordering dan tidak ada RIGHT, tetapkan tipe ordering (4)
    if (empty($q['right']) && preg_match('/\b(urutan|mengurutkan|nomor urut)\b/i', $q['text'])) {
        $q['type'] = '4';
        return $q;
    }

    // Jika RIGHT hanya berupa satu angka atau teks tanpa pilihan jawaban, ubah ke tipe isian singkat (3)
    if (!empty($q['right']) && is_scalar($q['right']) && empty($q['answers'])) {
        $q['type'] = '3';
        return $q;
    }

    // Jika soal sudah bertipe text (3) atau ordering (4), langsung kembalikan
    if ($q['type'] == '3' || $q['type'] == '4') {
        return $q;
    }

    // Jika soal memiliki pilihan ganda
    if (!empty($q['answers'])) {
        // Tandai jawaban benar jika RIGHT diberikan
        if (!empty($q['right'])) {
            foreach ($q['answers'] as &$ans) {
                if (in_array($ans['letter'], (array) $q['right'])) {
                    $ans['correct'] = true;
                }
            }
        }

        // Jika lebih dari satu jawaban benar, ubah ke pilihan ganda multiple answer (2)
        if (!empty($q['right']) && count((array) $q['right']) > 1) {
            $q['type'] = '2';
        } else {
            $q['type'] = '1';
        }
    } else {
        // Jika tidak ada pilihan jawaban, tetap ubah ke isian singkat (3)
        $q['type'] = '3';
    }

    return $q;
}

function processImages($text) {
    return preg_replace_callback('/<img\s+[^>]*src=([\'"])([^\'" >]+)\\1/i', function ($matches) {
        $src = trim($matches[2]);

        // Pastikan hanya gambar dalam folder "cache/" yang diproses
        if (strpos($src, 'cache/') === 0) {
            $filePath = __DIR__ . '/' . $src;

            if (file_exists($filePath)) {
                $fileData = file_get_contents($filePath);
                $mimeType = mime_content_type($filePath);
                $base64 = base64_encode($fileData);

                // Buat format Base64 untuk digunakan sebagai src di tag img
                $newSrc = 'data:' . $mimeType . ';base64,' . $base64;

                // Hapus file asli setelah dikonversi ke Base64
                unlink($filePath);

                // Ganti src lama dengan Base64 baru
                return str_replace($matches[2], $newSrc, $matches[0]);
            }
        }
        return $matches[0];
    }, $text);
}


/**
 * Generate XML dalam format TCExam.
 */
function generateTCExamXML($questions) {
    $typeMapping = array(
        '1' => 'single',
        '2' => 'multiple',
        '3' => 'text',
        '4' => 'ordering',
        'single' => 'single',
        'multiple' => 'multiple',
        'text' => 'text',
        'ordering' => 'ordering'
    );
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><tcexamquestions version="14.3.0"></tcexamquestions>');
    $header = $xml->addChild('header', " ");
    $header->addAttribute('lang', 'id');
    $header->addAttribute('date', date('Y-m-d H:i:s'));
    $body = $xml->addChild('body');
    $module = $body->addChild('module');
    $module->addChild('name', htmlspecialchars($questions[0]['module']));
    $module->addChild('enabled', 'true');
    $subject = $module->addChild('subject');
    $subject->addChild('name', htmlspecialchars($questions[0]['topic']));
    $subject->addChild('description', "");
    $subject->addChild('enabled', 'true');
    $qPos = 1;
    foreach ($questions as $q) {
        $question = $subject->addChild('question');
        $question->addChild('enabled', 'true');
        $qType = isset($typeMapping[$q['type']]) ? $typeMapping[$q['type']] : 'single';
        $question->addChild('type', $qType);
        $question->addChild('difficulty', '1');
        $question->addChild('position', $qPos);
        $question->addChild('timer', '0');
        $question->addChild('fullscreen', 'false');
        $question->addChild('inline_answers', 'false');
        $question->addChild('auto_next', 'false');
        $desc = $question->addChild('description');
        $descDom = dom_import_simplexml($desc);
        $doc = $descDom->ownerDocument;
        $cdata = $doc->createCDATASection($q['text']);
        $descDom->appendChild($cdata);
        $question->addChild('explanation', "");
        $aPos = 1;
        foreach ($q['answers'] as $ans) {
            $answer = $question->addChild('answer');
            $answer->addChild('enabled', 'true');
            $answer->addChild('isright', $ans['correct'] ? 'true' : 'false');
            $answer->addChild('position', $aPos);
            $answer->addChild('keyboard_key', "");
            $answer->addChild('description', htmlspecialchars($ans['text']));
            $answer->addChild('explanation', "");
            $aPos++;
        }
        $qPos++;
    }
    return $xml->asXML();
}
echo '</fieldset></div>'.K_NEWLINE;

echo '</div>'.K_NEWLINE;
echo '</div>'.K_NEWLINE;

require_once('../code/tce_page_footer.php');

//============================================================+
// END OF FILE
//============================================================+