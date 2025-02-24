# cosmira/connect

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
