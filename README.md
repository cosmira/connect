# Serenade

[![Tests](https://github.com/esplora/decompresso/actions/workflows/phpunit.yml/badge.svg)](https://github.com/esplora/decompresso/actions/workflows/phpunit.yml)
[![Quality Assurance](https://github.com/esplora/lumos/actions/workflows/quality.yml/badge.svg)](https://github.com/esplora/lumos/actions/workflows/quality.yml)
[![Coding Guidelines](https://github.com/esplora/lumos/actions/workflows/php-cs-fixer.yml/badge.svg)](https://github.com/esplora/lumos/actions/workflows/php-cs-fixer.yml)


# Simple SMTP Server on Pure PHP

This is a basic SMTP server implemented in pure PHP. It supports several SMTP commands for handling email communication.

### Supported Commands:

- **HELO**: Initiates the communication between the client and server.
- **MAIL**: Specifies the sender's email address.
- **RCPT**: Specifies the recipient's email address.
- **DATA**: Indicates the start of the email message.
- **MESSAGE**: Defines the email message content.
- **QUIT**: Terminates the communication session.

## Usage

To run the server:

```shell
php server.php
```

### Example:

Sending a test email:
```shell
swaks --to recipient@example.com --from sender@example.com --server 127.0.0.1 --port 2525
```

### Sending to Multiple Recipients:

```shell
swaks --to recipient1@example.com,recipient2@example.com --from sender@example.com --server 127.0.0.1 --port 2525 \
      --body "Test with multiple recipients"
```

### Testing with Attachments:

```shell
swaks --to recipient@example.com --from sender@example.com --server 127.0.0.1 --port 2525 \
      --attach @/path/to/attachment.txt
```

Так же обертка:

# MailParser

## Introduction

`MailParser` is a simple and flexible email parser for PHP, built on top of the powerful [ZBateson\MailMimeParser](https://github.com/ZBateson/MailMimeParser) package. It provides a clean and intuitive API designed to make parsing email content, extracting headers, and handling attachments effortless.

## Installation

You can install this package via Composer:

```bash
composer require esplora/lumos-mail-parser
```

## Usage

### Basic Usage

To use the `MailParser` class, simply instantiate it with the raw email content. You can then use various methods to retrieve different parts of the email such as the sender, recipient, subject, and more.

```php
use Esplora\Lumos\MailParser;

$emailContent = file_get_contents('path_to_email_file.txt');

// Parse the email content
$mailParser = MailParser::fromString($emailContent);

// Retrieve the sender's email address
$from = $mailParser->from();

// Retrieve the recipient(s) in the "To" field
$to = $mailParser->to();

// Retrieve the email subject
$subject = $mailParser->subject();

// Retrieve the plain text content
$textContent = $mailParser->text();

// Retrieve the HTML content
$htmlContent = $mailParser->html();

// Retrieve the attachments
$attachments = $mailParser->attachments();
```

### Available Methods

Here are the available methods you can use with the `MailParser`:

- `from()` – Get the sender's email address.
- `fromName()` – Get the sender's name.
- `to()` – Get the "To" recipients, as a collection of email => name pairs.
- `cc()` – Get the "CC" recipients, as a collection of email => name pairs.
- `bcc()` – Get the "BCC" recipients, as a collection of email => name pairs.
- `subject()` – Get the subject of the email.
- `date()` – Get the date the email was sent as a `DateTimeImmutable` object.
- `text()` – Get the plain text content of the email.
- `html()` – Get the HTML content of the email.
- `attachments()` – Get a collection of attachments with their filenames, MIME types, and content.

### Example: Retrieving All Recipients

```php
// Get the "To" field recipients
$toRecipients = $mailParser->to();

// Get the "CC" field recipients
$ccRecipients = $mailParser->cc();

// Get the "BCC" field recipients
$bccRecipients = $mailParser->bcc();
```

### Example: Retrieving Attachments

```php
// Get all attachments in the email
$attachments = $mailParser->attachments();

// Loop through attachments and get information about each
foreach ($attachments as $attachment) {
    echo 'Filename: ' . $attachment['name'] . PHP_EOL;
    echo 'MIME Type: ' . $attachment['mime'] . PHP_EOL;
    echo 'Content: ' . base64_encode($attachment['content']) . PHP_EOL;
}
```







## License

This package is licensed under the MIT License. See the [LICENSE](LICENSE.md) file for details.
