# Security Implementation Guide

## Overview
This document outlines the comprehensive security measures implemented in the Sakai Vue API backend to protect against common web vulnerabilities and attacks.

## Security Features Implemented

### 1. Authentication & Authorization
- **JWT-based Authentication**: Secure token-based authentication using Firebase JWT
- **Role-Based Access Control (RBAC)**: Three-tier permission system (User, Moderator, Admin)
- **Password Security**: Argon2ID hashing with strong password validation
- **Session Management**: Stateless authentication with token expiration

### 2. Input Validation & Sanitization
- **JSON Input Validation**: Strict JSON parsing with error handling
- **Data Sanitization**: Automatic sanitization of all user inputs
- **SQL Injection Prevention**: Prepared statements and parameterized queries
- **XSS Protection**: Input sanitization and output encoding

### 3. Rate Limiting & Throttling
- **API Rate Limiting**: Configurable rate limits per endpoint
- **IP-based Throttling**: Protection against brute force attacks
- **Request Size Limits**: Maximum request size enforcement

### 4. Security Headers
- **Content Security Policy (CSP)**: Prevents XSS attacks
- **X-Frame-Options**: Prevents clickjacking
- **X-Content-Type-Options**: Prevents MIME type sniffing
- **Strict-Transport-Security**: Enforces HTTPS
- **X-XSS-Protection**: Browser XSS protection

### 5. Data Protection
- **Encryption**: AES-256-CBC encryption for sensitive data
- **Secure Configuration**: Environment-based configuration management
- **Database Security**: PDO with prepared statements

### 6. Logging & Monitoring
- **Security Event Logging**: Comprehensive logging of security events
- **Audit Trail**: User actions and access attempts
- **Error Handling**: Secure error messages without information disclosure

## Configuration

### Environment Variables
Create a `.env` file in the backend root directory:

```env
# Database Configuration
DB_HOST=localhost
DB_NAME=sakai_vue_api
DB_USERNAME=your_db_username
DB_PASSWORD=your_secure_password
DB_CHARSET=utf8mb4

# Security Configuration
JWT_SECRET_KEY=your-super-secret-jwt-key-change-this-in-production
ENCRYPTION_KEY=your-32-character-encryption-key-here
API_RATE_LIMIT=100
API_RATE_WINDOW=3600

# Application Configuration
APP_NAME="Sakai Vue API"
APP_VERSION="1.0.0"
APP_DEBUG=false
APP_ENV=production

# CORS Configuration
CORS_ORIGINS=http://localhost:3000,http://127.0.0.1:3000,http://localhost:5173,http://127.0.0.1:5173
```

### Database Setup
Run the migration to create the users table:

```bash
php yii migrate
```

## API Endpoints

### Authentication Endpoints
- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration
- `POST /api/auth/logout` - User logout
- `POST /api/auth/refresh` - Refresh JWT token
- `GET /api/auth/profile` - Get user profile
- `POST /api/auth/change-password` - Change password

### Protected Endpoints
All other API endpoints require authentication via JWT token in the Authorization header:
```
Authorization: Bearer <your-jwt-token>
```

## Security Best Practices

### 1. Password Requirements
- Minimum 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one number
- At least one special character

### 2. Token Management
- JWT tokens expire after 1 hour
- Use refresh tokens for long-term sessions
- Store tokens securely on the client side

### 3. Rate Limiting
- Default: 100 requests per hour per IP
- Configurable per endpoint
- Automatic blocking of excessive requests

### 4. Input Validation
- All inputs are automatically sanitized
- JSON data is validated before processing
- File uploads are restricted by type and size

## Security Monitoring

### Log Files
Security events are logged to `runtime/logs/app.log` with the following event types:
- `login_success` - Successful login
- `login_failed` - Failed login attempt
- `user_registered` - New user registration
- `password_changed` - Password change
- `access_denied` - Unauthorized access attempt
- `api_request` - API request logging

### Monitoring Recommendations
1. Monitor failed login attempts
2. Watch for unusual API usage patterns
3. Check for suspicious user agents
4. Monitor file upload activities
5. Review access denied events

## Deployment Security

### Production Checklist
- [ ] Change all default passwords
- [ ] Update JWT secret key
- [ ] Set strong encryption key
- [ ] Configure proper CORS origins
- [ ] Enable HTTPS
- [ ] Set up proper file permissions
- [ ] Configure firewall rules
- [ ] Set up log rotation
- [ ] Enable database encryption
- [ ] Regular security updates

### File Permissions
```bash
# Set proper permissions
chmod 755 backend/
chmod 644 backend/config/*.php
chmod 600 backend/.env
chmod 777 backend/runtime/
chmod 777 backend/web/assets/
```

## Common Security Issues Addressed

1. **SQL Injection**: Prevented with prepared statements
2. **XSS Attacks**: Mitigated with input sanitization and CSP
3. **CSRF Attacks**: Protected with CSRF tokens
4. **Brute Force**: Rate limiting and account lockout
5. **Session Hijacking**: JWT with short expiration
6. **Data Exposure**: Encryption for sensitive data
7. **Directory Traversal**: Input validation
8. **File Upload Attacks**: Type and size restrictions

## Security Testing

### Manual Testing
1. Test authentication endpoints
2. Verify rate limiting
3. Check input validation
4. Test file upload restrictions
5. Verify security headers

### Automated Testing
```bash
# Run security tests
php yii test/security
```

## Incident Response

### Security Breach Response
1. Immediately revoke all JWT tokens
2. Change all passwords and keys
3. Review access logs
4. Update security measures
5. Notify affected users

### Contact Information
- Security Team: security@sakai-vue.com
- Emergency: +1-XXX-XXX-XXXX

## Updates and Maintenance

### Regular Tasks
- Update dependencies monthly
- Review security logs weekly
- Rotate encryption keys quarterly
- Conduct security audits annually

### Security Updates
- Subscribe to Yii2 security announcements
- Monitor PHP security updates
- Keep JWT library updated
- Review and update security policies

---

**Note**: This security implementation provides a strong foundation, but security is an ongoing process. Regular updates, monitoring, and testing are essential for maintaining a secure application.
