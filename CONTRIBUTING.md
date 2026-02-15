# Contributing to HR Management System

First off, thank you for considering contributing to HR Management System! It's people like you that make this system better for everyone.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the existing issues to see if the problem has already been reported. When you are creating a bug report, please include as many details as possible:

- **Use a clear and descriptive title**
- **Describe the exact steps to reproduce the problem**
- **Provide specific examples to demonstrate the steps**
- **Describe the behavior you observed and what behavior you expected**
- **Include screenshots if applicable**

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion, please include:

- **Use a clear and descriptive title**
- **Provide a step-by-step description of the suggested enhancement**
- **Provide specific examples to demonstrate the enhancement**
- **Explain why this enhancement would be useful**

### Pull Requests

1. Fork the repository
2. Create a new branch from `main` for your feature or bug fix
3. Make your changes
4. Test your changes thoroughly
5. Update documentation if needed
6. Submit a pull request

## Development Setup

1. Clone your fork:
```bash
git clone https://github.com/YOUR_USERNAME/hrm-system.git
cd hrm-system
```

2. Create a branch:
```bash
git checkout -b feature/my-new-feature
# or
git checkout -b bugfix/fix-some-bug
```

3. Make your changes and commit:
```bash
git add .
git commit -m "Add some feature"
```

4. Push to your fork:
```bash
git push origin feature/my-new-feature
```

5. Open a Pull Request

## Coding Standards

- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Comment your code where necessary
- Write clean, readable code
- Use Arabic for user-facing text, English for code comments

## Commit Message Guidelines

- Use the present tense ("Add feature" not "Added feature")
- Use the imperative mood ("Move cursor to..." not "Moves cursor to...")
- Limit the first line to 72 characters or less
- Reference issues and pull requests liberally after the first line

Example:
```
Add employee export to Excel functionality

- Implement PhpSpreadsheet integration
- Add export button to employees list
- Support filtering before export
- Fix #123
```

## Testing

Before submitting a pull request:

1. Test your changes locally
2. Run the test suite: `php test_all.php`
3. Check for PHP errors and warnings
4. Verify database migrations work correctly
5. Test on different browsers if UI changes were made

## Documentation

- Update the README.md if you change functionality
- Add comments to complex code sections
- Update API documentation if endpoints change

## Questions?

Feel free to open an issue with your question or contact the maintainers.

Thank you for contributing! 🎉