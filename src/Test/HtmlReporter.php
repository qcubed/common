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

namespace QCubed\Test;


/**
 * HtmlReporter for local test case running.
 *
 * Class HtmlReporter
 * @package QCubed\Test
 */
class HtmlReporter extends \PHPUnit_TextUI_ResultPrinter
{
    protected $results;
    protected $currentSuite;
    protected $currentTest;

    public function __construct(
        $out = null,
        $verbose = false,
        $colors = self::COLOR_DEFAULT,
        $debug = false,
        $columns = 80,
        $reverseList = false
    ) {
        ob_start(); // start output buffering, so we can send the output to the browser in chunks

        $this->autoFlush = true;

        parent::__construct($out, $verbose, $colors, $debug, $columns, $reverseList);
    }


    public function write($buffer)
    {
        $buffer = nl2br($buffer);

        $buffer = str_pad($buffer,
                1024) . "\n"; // pad the string, otherwise the browser will do nothing with the flushed output

        if ($this->out) {
            fwrite($this->out, $buffer);

            if ($this->autoFlush) {
                $this->incrementalFlush();
            }
        } else {
            print $buffer;

            if ($this->autoFlush) {
                $this->incrementalFlush();
            }
        }
    }

    public function incrementalFlush()
    {
        if ($this->out) {
            fflush($this->out);
        } else {
            ob_flush(); // flush the buffered output
            flush();
        }
    }


    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        $this->currentSuite = $suite->getName();
    }

    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        $this->currentSuite = null;
    }


    public function startTest(\PHPUnit_Framework_Test $test)
    {
        $this->currentTest = $test->getName();
        $this->results[$this->currentSuite][$test->getName()]['test'] = $test;
    }

    public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
        $this->results[$this->currentSuite][$test->getName()]['status'] = 'error';
        $this->results[$this->currentSuite][$test->getName()]['errors'][] = compact('e', 'time');
    }

    public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        $this->results[$this->currentSuite][$test->getName()]['status'] = 'failed';
        $this->results[$this->currentSuite][$test->getName()]['results'][] = compact('e', 'time');
    }

    public function addIncompleteTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
        $this->results[$this->currentSuite][$test->getName()]['status'] = 'incomplete';
        $this->results[$this->currentSuite][$test->getName()]['errors'][] = compact('e', 'time');
    }

    public function addSkippedTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
        $this->results[$this->currentSuite][$test->getName()]['status'] = 'skipped';
        $this->results[$this->currentSuite][$test->getName()]['errors'][] = compact('e', 'time');
    }

    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
        $t = &$this->results[$this->currentSuite][$test->getName()];
        if (!isset($t['status'])) {
            $t['status'] = 'passed';
        }
        $t['time'] = $time;
        $this->currentTest = null;
    }

    public function printResult(\PHPUnit_Framework_TestResult $result)
    {
        echo('<h1>QCubed Unit Tests - PHPUnit ' . \PHPUnit_Runner_Version::id() . '</h1>');

        foreach ($this->results as $suiteName => $suite) {
            $strHtml = "<b>$suiteName</b><br />";
            foreach ($suite as $testName => $test) {
                $status = $test['status'];
                $status = ucfirst($status);
                if ($test['status'] !== 'passed') {
                    $status = '<span style="color:red">' . $status . '</span>';
                } else {
                    $status = '<span style="color:green">' . $status . '</span>';
                }

                $strHtml .= "$status: $testName";
                $strHtml = "$strHtml<br />";
                if (isset($test['errors'])) {
                    foreach ($test['errors'] as $error) {
                        $strHtml .= nl2br(htmlentities($error['e']->__toString())) . '<br />';
                    }
                }
                if (isset($test['results'])) {
                    foreach ($test['results'] as $error) {
                        $strMessage = $error['e']->__toString() . "\n";
                        // get first line
                        $lines = explode("\n", \PHPUnit_Util_Filter::getFilteredStacktrace($error['e']));
                        $strMessage .= $lines[0] . "\n";
                        $strHtml .= nl2br(htmlentities($strMessage)) . '<br />';
                    }
                }
            }
            echo $strHtml;
        }

        $str = "\nRan " . $result->count() . " tests in " . $result->time() . " seconds.\n";
        $str .= $result->failureCount() . " assertions failed.\n";
        $str .= $result->errorCount() . " exceptions were thrown.\n";
        echo nl2br($str);
    }
}
