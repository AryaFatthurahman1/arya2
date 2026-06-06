# HR Management System - API Documentation

## Overview

This document provides comprehensive API documentation for the HR Management System (HRIS) built with Laravel 11. The system includes modules for employee management, attendance tracking, leave requests, task assignment, performance evaluation, and payroll calculation.

## Base URL

```text
http://localhost:8000
```

## Authentication

All API endpoints require authentication except the login endpoint. Use session-based authentication or Laravel Sanctum tokens.

### Login

**Endpoint:** `POST /login`

**Request Body:**

```json
{
  "email": "admin@hr.test",
  "password": "password",
  "remember": false
}
```

**Response:**

- Success: Redirects to dashboard
- Failure: Returns validation errors

### Logout

**Endpoint:** `POST /logout`

**Headers:** Requires authenticated session

---

## Modules

### 1. Karyawan (Employee Management)

#### Get All Employees

**Endpoint:** `GET /karyawan`

**Permissions:** `view karyawan`

**Query Parameters:**

- `search` (string): Search by name or NIK
- `page` (integer): Page number (default: 1)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "nik": "ADM001",
      "nama_lengkap": "Muhammad Arya Fatthurahman",
      "email": "admin@hr.test",
      "jabatan": {
        "id": 1,
        "nama_jabatan": "Direktur"
      },
      "satuan_kerja": {
        "id": 1,
        "nama_satuan_kerja": "IT Development"
      }
    }
  ],
  "current_page": 1,
  "per_page": 10
}
```

#### Create Employee
**Endpoint:** `POST /karyawan`

**Permissions:** `create karyawan`

**Request Body:**
```json
{
  "nik": "EMP001",
  "nama_lengkap": "John Doe",
  "tempat_lahir": "Jakarta",
  "tanggal_lahir": "1990-01-01",
  "jenis_kelamin": "L",
  "agama": "Islam",
  "status_pernikahan": "menikah",
  "alamat": "Jl. Contoh No. 1",
  "telepon": "081234567890",
  "email": "john@example.com",
  "jabatan_id": 1,
  "satuan_kerja_id": 1,
  "tanggal_masuk": "2024-01-01",
  "status_karyawan": "tetap",
  "nama_bank": "BCA",
  "nomor_rekening": "1234567890"
}
```

#### Update Employee
**Endpoint:** `PUT /karyawan/{id}`

**Permissions:** `edit karyawan`

**Request Body:** Same as create employee

#### Delete Employee
**Endpoint:** `DELETE /karyawan/{id}`

**Permissions:** `delete karyawan`

#### Export Employees
**Endpoint:** `GET /karyawan/export`

**Permissions:** `view karyawan`

**Response:** Downloads Excel file

#### Import Employees
**Endpoint:** `POST /karyawan/import`

**Permissions:** `create karyawan`

**Request Body:** 
- `file` (file): Excel file (.xlsx, .xls, .csv)

---

### 2. Absensi (Attendance)

#### Get All Attendance Records
**Endpoint:** `GET /absensi`

**Permissions:** `view absensi`

**Query Parameters:**
- `tanggal_mulai` (date): Start date filter
- `tanggal_selesai` (date): End date filter
- `karyawan_id` (integer): Filter by employee
- `status` (string): Filter by status (hadir, izin, sakit, alpha)

#### Create Attendance Record
**Endpoint:** `POST /absensi`

**Permissions:** `create absensi`

**Request Body:**
```json
{
  "karyawan_id": 1,
  "tanggal": "2024-01-01",
  "jam_masuk": "08:00",
  "jam_keluar": "17:00",
  "status": "hadir",
  "keterangan": "Normal"
}
```

#### Online Check-in
**Endpoint:** `POST /absensi/clock-in`

**Permissions:** `create absensi`

**Request Body:**
```json
{
  "karyawan_id": 1
}
```

#### Export Attendance
**Endpoint:** `GET /absensi/export`

**Permissions:** `view absensi`

**Query Parameters:**
- `tahun` (integer): Year filter
- `bulan` (integer): Month filter

#### Import Attendance
**Endpoint:** `POST /absensi/import`

**Permissions:** `create absensi`

**Request Body:**
- `file` (file): Excel file from fingerprint machine

---

### 3. Izin (Leave Requests)

#### Get All Leave Requests
**Endpoint:** `GET /izin`

**Permissions:** `view izin`

#### Create Leave Request
**Endpoint:** `POST /izin`

**Permissions:** `create izin`

**Request Body:**
```json
{
  "karyawan_id": 1,
  "tipe_izin": "cuti_tahunan",
  "tanggal_mulai": "2024-01-01",
  "tanggal_selesai": "2024-01-03",
  "alasan": "Family event",
  "file_dokumen": "file.pdf"
}
```

#### Approve Leave Request
**Endpoint:** `PATCH /izin/{id}/approve`

**Permissions:** `approve izin`

#### Reject Leave Request
**Endpoint:** `PATCH /izin/{id}/reject`

**Permissions:** `approve izin`

---

### 4. Tugas (Task Assignment)

#### Get All Tasks
**Endpoint:** `GET /tugas`

**Permissions:** `view tugas`

#### Create Task
**Endpoint:** `POST /tugas`

**Permissions:** `create tugas`

**Request Body:**
```json
{
  "karyawan_id": 1,
  "judul_tugas": "Complete project report",
  "deskripsi": "Detailed task description",
  "prioritas": "tinggi",
  "deadline": "2024-01-15"
}
```

#### Update Task Status
**Endpoint:** `POST /tugas/{id}/status`

**Permissions:** `manage tasks`

**Request Body:**
```json
{
  "status": "selesai",
  "catatan": "Task completed successfully"
}
```

---

### 5. Penilaian (Performance Evaluation)

#### Get All Evaluations
**Endpoint:** `GET /penilaian`

**Permissions:** `view penilaian`

#### Create Evaluation
**Endpoint:** `POST /penilaian`

**Permissions:** `create penilaian`

**Request Body:**
```json
{
  "karyawan_id": 1,
  "periode_penilaian": "2024-Q1",
  "skor_kedisiplinan": 85,
  "skor_kualitas": 90,
  "skor_kerjasama": 88,
  "skor_inisiatif": 82,
  "catatan": "Excellent performance"
}
```

---

### 6. Penggajian (Payroll)

#### Get All Payroll Records
**Endpoint:** `GET /penggajian`

**Permissions:** `view penggajian`

#### Create Payroll
**Endpoint:** `POST /penggajian`

**Permissions:** `create penggajian`

**Request Body:**
```json
{
  "karyawan_id": 1,
  "periode": "2024-01",
  "gaji_pokok": 15000000,
  "tunjangan_jabatan": 5000000,
  "tunjangan_transport": 1500000,
  "tunjangan_makan": 1000000,
  "lembur": 0,
  "potongan": 0
}
```

#### Mark as Paid
**Endpoint:** `GET /penggajian/{id}/paid`

**Permissions:** `manage penggajian`

#### Print Payslip
**Endpoint:** `GET /penggajian/{id}/slip`

**Permissions:** `view penggajian`

#### Export Payroll
**Endpoint:** `GET /penggajian/export`

**Permissions:** `view penggajian`

---

### 7. Dokumen (Document Management)

#### Get All Documents
**Endpoint:** `GET /dokumen`

**Permissions:** `view dokumen`

**Query Parameters:**
- `search` (string): Search by document name
- `tipe_dokumen` (string): Filter by type (kontrak, sk, sertifikat, personal, lainnya)
- `karyawan_id` (integer): Filter by employee

#### Upload Document
**Endpoint:** `POST /dokumen`

**Permissions:** `create dokumen`

**Request Body:**
```json
{
  "nama_dokumen": "Employment Contract",
  "tipe_dokumen": "kontrak",
  "file": "contract.pdf",
  "karyawan_id": 1,
  "deskripsi": "Annual employment contract"
}
```

**Supported File Types:**
- PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX
- TXT, CSV, ZIP
- JPG, JPEG, PNG, GIF, SVG
- Max file size: 20MB

#### Download Document
**Endpoint:** `GET /dokumen/{id}/download`

**Permissions:** `view dokumen`

#### Delete Document
**Endpoint:** `DELETE /dokumen/{id}`

**Permissions:** `delete dokumen`

---

### 8. Jabatan (Positions)

#### Get All Positions
**Endpoint:** `GET /jabatan`

**Permissions:** `view jabatan`

#### Create Position
**Endpoint:** `POST /jabatan`

**Permissions:** `create jabatan`

**Request Body:**
```json
{
  "nama_jabatan": "Senior Developer",
  "gaji_pokok": 10000000,
  "tunjangan_jabatan": 3000000,
  "tunjangan_transport": 1000000,
  "tunjangan_makan": 800000
}
```

---

### 9. Satuan Kerja (Work Units)

#### Get All Work Units
**Endpoint:** `GET /satuan-kerja`

**Permissions:** `view satuan-kerja`

#### Create Work Unit
**Endpoint:** `POST /satuan-kerja`

**Permissions:** `create satuan-kerja`

**Request Body:**
```json
{
  "nama_satuan_kerja": "Marketing Department",
  "lokasi": "Jakarta"
}
```

---

## Roles and Permissions

### Admin
Full access to all modules and features.

### Atasan
- View and create employees
- View positions and work units
- View, create, and edit attendance
- View and approve leave requests
- View, create, and manage tasks
- View, create, and edit performance evaluations
- View, create, and manage payroll

### Karyawan
- View employees
- View and create attendance (own)
- View and create leave requests (own)
- View tasks (assigned to self)
- View performance evaluations (own)
- View payroll (own)

---

## Error Responses

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "message": "You do not have permission to perform this action."
}
```

### Not Found (404)
```json
{
  "message": "Resource not found."
}
```

### Server Error (500)
```json
{
  "message": "Internal server error."
}
```

---

## Security Features

1. **CSRF Protection:** All POST/PUT/DELETE requests require CSRF token
2. **Input Validation:** All inputs are validated before processing
3. **XSS Protection:** Output is escaped by default
4. **SQL Injection Protection:** Uses parameterized queries via Eloquent ORM
5. **File Upload Security:** File type validation, size limits, and secure storage
6. **Data Encryption:** Sensitive fields (address, phone, bank account) are encrypted using AES-256
7. **RBAC:** Role-based access control with granular permissions
8. **Rate Limiting:** Login attempts are throttled
9. **Session Management:** Secure session handling with expiration

---

## Rate Limiting

Login endpoint: 5 attempts per minute

---

## File Upload Limits

- Maximum file size: 20MB
- Supported formats: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, CSV, ZIP, JPG, JPEG, PNG, GIF, SVG

---

## Data Encryption

The following fields are encrypted using AES-256:
- `karyawan.alamat`
- `karyawan.telepon`
- `karyawan.nomor_rekening`

Encryption is handled automatically by Laravel's attribute casting.

---

## Pagination

All list endpoints support pagination with default 15-20 items per page.

---

## Search and Filtering

Most list endpoints support search and filtering via query parameters.

---

## Excel Import/Export

- Employees: Import/Export supported
- Attendance: Import/Export supported
- Payroll: Export supported

---

## Version

Current API Version: 1.0.0

Laravel Version: 11.x

PHP Version: 8.2+

---

## Support

For technical support, contact the development team or refer to the IT/IJK Technical Guide.
