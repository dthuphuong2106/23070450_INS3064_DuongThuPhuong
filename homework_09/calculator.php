<?php
/**
 * Web 2 - Calculator Application
 * Student Name: Duong Thu Phuong
 * Student ID: 23070450
 * Date: 2026-11-05
 */


declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '0');

session_start();

/* ================= CUSTOM EXCEPTIONS ================= */

class DivisionByZeroException extends Exception {}
class InvalidOperatorException extends Exception {}
class InvalidInputException extends Exception {}

/* ================= HELPER FUNCTION ================= */

function h(string $value): string
{
    return htmlspecialchars(
        $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

/* ================= ERROR LOGGING ================= */

function logCalculatorError(
    Throwable $error,
    string $expression
): void {

    $timestamp = date('Y-m-d H:i:s');

    $type = get_class($error);

    $message = str_replace(
        ["\r", "\n", "|"],
        [' ', ' ', '/'],
        $error->getMessage()
    );

    $input = str_replace(
        ["\r", "\n", '"'],
        [' ', ' ', '\\"'],
        $expression
    );

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    $line = sprintf(
        '[%s] %s: %s | Input: "%s" | IP: %s%s',
        $timestamp,
        $type,
        $message,
        $input,
        $ip,
        PHP_EOL
    );

    $logPath = __DIR__ . DIRECTORY_SEPARATOR
        . 'calculator_errors.log';

    $written = @file_put_contents(
        $logPath,
        $line,
        FILE_APPEND | LOCK_EX
    );

    if ($written === false) {
        error_log(
            'Calculator error log could not be written.'
        );
    }
}

/* ================= INPUT VALIDATION ================= */

function parseNumber(
    string $raw,
    string $fieldName
): int|float {

    $trimmed = trim($raw);

    if ($trimmed === '' || !is_numeric($trimmed)) {

        throw new InvalidInputException(
            $fieldName . ' must be a valid number.'
        );
    }

    $number = $trimmed + 0;

    if (
        is_float($number)
        && !is_finite($number)
    ) {

        throw new InvalidInputException(
            $fieldName
            . ' is outside the supported numeric range.'
        );
    }

    return $number;
}

/* ================= CALCULATION FUNCTION ================= */

function calculate(
    int|float $a,
    string $operator,
    int|float $b
): int|float {

    $allowed = [
        '+',
        '-',
        '*',
        '/',
        '%',
        '**'
    ];

    if (!in_array($operator, $allowed, true)) {

        throw new InvalidOperatorException(
            'Please choose a supported operator.'
        );
    }

    /* Division by zero and modulo by zero */

    if (
        ($operator === '/' || $operator === '%')
        && $b == 0
    ) {

        throw new DivisionByZeroException(
            'The second number must not be zero for this operation.'
        );
    }

    /* Modulo requires whole numbers */

    if (
        $operator === '%'
        && (
            floor((float)$a) != $a
            || floor((float)$b) != $b
        )
    ) {

        throw new InvalidInputException(
            'Modulo (%) requires whole-number operands.'
        );
    }

    /* Perform calculation */

    switch ($operator) {

        case '+':

            $result = $a + $b;

            break;

        case '-':

            $result = $a - $b;

            break;

        case '*':

            $result = $a * $b;

            break;

        case '/':

            $result = $a / $b;

            break;

        case '%':

            $result = (int)$a % (int)$b;

            break;

        case '**':

            $result = $a ** $b;

            break;

        default:

            throw new InvalidOperatorException(
                'Please choose a supported operator.'
            );
    }

    /* Check overflow */

    if (
        is_float($result)
        && !is_finite($result)
    ) {

        throw new OverflowException(
            'The result exceeds PHP numeric limits.'
        );
    }

    if (
        is_float($result)
        && abs($result) > PHP_INT_MAX
    ) {

        throw new OverflowException(
            'The result exceeds PHP integer limits.'
        );
    }

    return $result;
}

/* ================= RESULT FORMATTING ================= */

function formatResult(
    int|float $result,
    string $operator
): string {

    /* Division: 4 decimal places */

    if ($operator === '/') {

        return number_format(
            (float)$result,
            4,
            '.',
            ''
        );
    }

    /* Integer results */

    if (
        is_int($result)
        || (
            is_float($result)
            && floor($result) === $result
            && abs($result) <= PHP_INT_MAX
        )
    ) {

        return (string)(int)$result;
    }

    /* Other finite decimal results */

    return rtrim(
        rtrim(
            sprintf('%.10f', (float)$result),
            '0'
        ),
        '.'
    );
}

/* ================= SESSION HISTORY ================= */

if (
    !isset($_SESSION['history'])
    || !is_array($_SESSION['history'])
) {

    $_SESSION['history'] = [];
}

/* ================= INITIAL VALUES ================= */

$firstInput = '';

$secondInput = '';

$operatorInput = '+';

$message = '';

$messageType = '';

$resultText = '';

$expressionText = '';

/* ================= HANDLE FORM SUBMISSION ================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = isset($_POST['action'])
        && is_string($_POST['action'])
        ? $_POST['action']
        : 'calculate';

    /* Clear history */

    if ($action === 'clear') {

        $_SESSION['history'] = [];

        header(
            'Location: '
            . strtok($_SERVER['REQUEST_URI'], '?')
        );

        exit;
    }

    /* Get form values */

    $firstInput =
        isset($_POST['first_number'])
        && is_string($_POST['first_number'])
        ? trim($_POST['first_number'])
        : '';

    $secondInput =
        isset($_POST['second_number'])
        && is_string($_POST['second_number'])
        ? trim($_POST['second_number'])
        : '';

    $operatorInput =
        isset($_POST['operator'])
        && is_string($_POST['operator'])
        ? $_POST['operator']
        : '+';

    /* Build expression */

    $expressionText = sprintf(
        '%s %s %s',
        $firstInput,
        $operatorInput,
        $secondInput
    );

    try {

        /* Validate input */

        $a = parseNumber(
            $firstInput,
            'First number'
        );

        $b = parseNumber(
            $secondInput,
            'Second number'
        );

        /* Calculate */

        $calculated = calculate(
            $a,
            $operatorInput,
            $b
        );

        /* Format result */

        $resultText = formatResult(
            $calculated,
            $operatorInput
        );

        /* Success message */

        $message =
            'Calculation completed successfully.';

        $messageType = 'success';

        /* Save successful calculation */

        $_SESSION['history'][] = [

            'expression' => $expressionText,

            'result' => $resultText,

            'timestamp' => date(
                'Y-m-d H:i:s'
            )

        ];

    } catch (
        InvalidInputException
        | InvalidOperatorException
        | DivisionByZeroException
        | OverflowException $error
    ) {

        /* Log specific errors */

        logCalculatorError(
            $error,
            $expressionText
        );

        /* Display friendly message */

        $message = $error->getMessage();

        $messageType = 'error';

    } catch (Throwable $error) {

        /* Log unexpected errors */

        logCalculatorError(
            $error,
            $expressionText
        );

        /* Do not show raw PHP errors */

        $message =
            'An unexpected error occurred. '
            . 'Please check your input and try again.';

        $messageType = 'error';
    }
}

/* Newest calculation first */

$history = array_reverse(
    $_SESSION['history']
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>PHP Calculator</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 32px 16px;
            font-family: Arial, Helvetica, sans-serif;
            background: #f3f6fb;
            color: #1f2937;
        }

        .container {
            max-width: 820px;
            margin: 0 auto;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 22px;
            box-shadow:
                0 4px 18px rgba(
                    15,
                    23,
                    42,
                    0.08
                );
        }

        h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        h2 {
            margin-top: 0;
        }

        .subtitle {
            margin: 0 0 22px;
            color: #64748b;
        }

        .form-grid {
            display: grid;
            grid-template-columns:
                1fr 150px 1fr;
            gap: 14px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            font-size: 14px;
        }

        input,
        select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 16px;
            background: #ffffff;
        }

        input:focus,
        select:focus {
            outline: 2px solid #93c5fd;
            border-color: #2563eb;
        }

        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 18px;
            flex-wrap: wrap;
        }

        button {
            border: 0;
            border-radius: 7px;
            padding: 11px 18px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        .calculate {
            background: #2563eb;
            color: #ffffff;
        }

        .clear {
            background: #e5e7eb;
            color: #1f2937;
        }

        .message {
            padding: 13px 15px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .result {
            margin-top: 16px;
            padding: 15px;
            background: #eff6ff;
            border-radius: 8px;
        }

        .result strong {
            display: block;
            margin-bottom: 5px;
            color: #1d4ed8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            text-align: left;
            padding: 12px 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f8fafc;
            font-size: 13px;
            color: #475569;
        }

        td {
            overflow-wrap: anywhere;
        }

        .empty {
            color: #64748b;
            padding: 16px 0;
        }

        @media (max-width: 620px) {

            .form-grid {
                grid-template-columns: 1fr;
            }

            .card {
                padding: 18px;
            }

            h1 {
                font-size: 24px;
            }

            table {
                font-size: 13px;
            }

            th,
            td {
                padding: 9px 6px;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <!-- CALCULATOR FORM -->

    <section class="card">

        <h1>PHP Calculator</h1>

        <p class="subtitle">
            Enter two numbers, choose an operator,
            and calculate.
        </p>

        <!-- Display message -->

        <?php if ($message !== ''): ?>

            <div
                class="message <?= h($messageType) ?>"
                role="status"
            >

                <?= h($message) ?>

            </div>

        <?php endif; ?>

        <!-- Calculator form -->

        <form
            method="POST"
            action="calculator.php"
        >

            <input
                type="hidden"
                name="action"
                value="calculate"
            >

            <div class="form-grid">

                <!-- First number -->

                <div>

                    <label for="first_number">
                        First Number
                    </label>

                    <input
                        type="text"
                        id="first_number"
                        name="first_number"
                        value="<?= h($firstInput) ?>"
                        placeholder="e.g. 10"
                        required
                    >

                </div>

                <!-- Operator -->

                <div>

                    <label for="operator">
                        Operator
                    </label>

                    <select
                        id="operator"
                        name="operator"
                        required
                    >

                        <?php
                        foreach (
                            ['+', '-', '*', '/', '%', '**']
                            as $op
                        ):
                        ?>

                            <option
                                value="<?= h($op) ?>"
                                <?= $operatorInput === $op
                                    ? 'selected'
                                    : '' ?>
                            >

                                <?= h($op) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Second number -->

                <div>

                    <label for="second_number">
                        Second Number
                    </label>

                    <input
                        type="text"
                        id="second_number"
                        name="second_number"
                        value="<?= h($secondInput) ?>"
                        placeholder="e.g. 3"
                        required
                    >

                </div>

            </div>

            <!-- Calculate button -->

            <div class="buttons">

                <button
                    class="calculate"
                    type="submit"
                >

                    Calculate

                </button>

            </div>

        </form>

        <!-- Display result -->

        <?php if ($messageType === 'success'): ?>

            <div class="result">

                <strong>Result</strong>

                <?= h($expressionText) ?>

                =

                <?= h($resultText) ?>

            </div>

        <?php endif; ?>

    </section>

    <!-- CALCULATION HISTORY -->

    <section class="card">

        <h2>Calculation History</h2>

        <?php if (count($history) > 0): ?>

            <table>

                <thead>

                    <tr>

                        <th>Expression</th>

                        <th>Result</th>

                        <th>Timestamp</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($history as $record): ?>

                        <tr>

                            <td>

                                <?= h(
                                    (string)$record['expression']
                                ) ?>

                            </td>

                            <td>

                                <?= h(
                                    (string)$record['result']
                                ) ?>

                            </td>

                            <td>

                                <?= h(
                                    (string)$record['timestamp']
                                ) ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

            <!-- Clear history -->

            <form
                method="POST"
                action="calculator.php"
                class="buttons"
            >

                <input
                    type="hidden"
                    name="action"
                    value="clear"
                >

                <button
                    class="clear"
                    type="submit"
                >

                    Clear History

                </button>

            </form>

        <?php else: ?>

            <p class="empty">

                No calculations yet.
                Your successful calculations
                will appear here.

            </p>

            <form
                method="POST"
                action="calculator.php"
                class="buttons"
            >

                <input
                    type="hidden"
                    name="action"
                    value="clear"
                >

                <button
                    class="clear"
                    type="submit"
                >

                    Clear History

                </button>

            </form>

        <?php endif; ?>

    </section>

</div>

</body>

</html>
