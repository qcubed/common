#!/usr/bin/env php
<?php
/**
 * MIT License
 *
 * Copyright (c) Shannon Pekary spekary@gmail.com
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * Process a script from the gen_sed command
 */

if (count($argv) < 3) {
    echo 'Usage: run_sed [-R] patternFile outFile(s)';
}

$options = getopt('R');
$blnRecursive = isset($options['R']);

$files = $argv;
array_shift($files);
for($i = 0; $i < count($options); $i++) {
    array_shift($files);
}
$patterns = include (array_shift($files));



function processFile($file) {
    global $patterns;

    $strFile = file_get_contents($file);

    $newFile = preg_replace(array_keys($patterns), array_values($patterns), $strFile);

    file_put_contents($file, $newFile);
}

function processFiles($files) {
    global $blnRecursive;

    foreach ($files as $file) {
        if (is_dir($file)) {
            if ($file != '.' &&
                $file != '..' &&
                $blnRecursive)
            {
                $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($file));
                $filter = new RegexIterator($objects, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
                foreach ($filter as $name=>$object) {
                    processFile($name);
                }
            }
        } else {
            processFile($file);
        }
    }
}

processFiles($files);

