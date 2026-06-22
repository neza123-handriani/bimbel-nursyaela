{
  "project": {
    "name": "Sistem Informasi Bimbingan Belajar",
    "version": "1.0",
    "technology": {
      "backend": "PHP Native",
      "database": "MySQL",
      "frontend": "HTML5, CSS3, JavaScript, Bootstrap 5",
      "payment_gateway": [
        "Midtrans",
        "Xendit"
      ]
    }
  },
  "modules": {
    "authentication": {
      "description": "Sistem login dan manajemen akun",
      "features": [
        "Login",
        "Logout",
        "Register",
        "Forgot Password",
        "Reset Password",
        "Role Management",
        "Session Management"
      ]
    },
    "admin": {
      "description": "Panel administrator",
      "features": [
        "Dashboard Statistik",
        "Manajemen User",
        "Manajemen Tutor",
        "Manajemen Kelas",
        "Manajemen Materi",
        "Manajemen Jadwal",
        "Manajemen Transaksi",
        "Verifikasi Pembayaran",
        "Laporan Pendapatan",
        "Pengaturan Website"
      ]
    },
    "user": {
      "description": "Panel siswa",
      "features": [
        "Dashboard User",
        "Profil User",
        "Daftar Kelas",
        "Pembelian Paket",
        "Riwayat Pembayaran",
        "Akses Materi",
        "Download Materi",
        "Lihat Jadwal",
        "Notifikasi"
      ]
    },
    "payment": {
      "description": "Integrasi pembayaran online",
      "features": [
        "Checkout Paket",
        "Generate Invoice",
        "Payment Gateway",
        "Webhook Callback",
        "Status Pembayaran",
        "Riwayat Transaksi"
      ]
    }
  },
  "pages": {
    "public": [
      {
        "name": "Landing Page",
        "url": "/"
      },
      {
        "name": "Tentang Kami",
        "url": "/tentang.php"
      },
      {
        "name": "Daftar Kelas",
        "url": "/kelas.php"
      },
      {
        "name": "Login",
        "url": "/login.php"
      },
      {
        "name": "Register",
        "url": "/register.php"
      }
    ],
    "admin": [
      {
        "name": "Dashboard",
        "url": "/admin/dashboard.php"
      },
      {
        "name": "Data User",
        "url": "/admin/users.php"
      },
      {
        "name": "Data Tutor",
        "url": "/admin/tutor.php"
      },
      {
        "name": "Data Kelas",
        "url": "/admin/kelas.php"
      },
      {
        "name": "Data Materi",
        "url": "/admin/materi.php"
      },
      {
        "name": "Data Jadwal",
        "url": "/admin/jadwal.php"
      },
      {
        "name": "Data Pembayaran",
        "url": "/admin/transaksi.php"
      },
      {
        "name": "Laporan",
        "url": "/admin/laporan.php"
      },
      {
        "name": "Pengaturan",
        "url": "/admin/settings.php"
      }
    ],
    "user": [
      {
        "name": "Dashboard",
        "url": "/user/dashboard.php"
      },
      {
        "name": "Profil",
        "url": "/user/profile.php"
      },
      {
        "name": "Kelas Saya",
        "url": "/user/kelas.php"
      },
      {
        "name": "Materi",
        "url": "/user/materi.php"
      },
      {
        "name": "Jadwal",
        "url": "/user/jadwal.php"
      },
      {
        "name": "Pembayaran",
        "url": "/user/pembayaran.php"
      },
      {
        "name": "Riwayat Transaksi",
        "url": "/user/transaksi.php"
      }
    ]
  },
  "database": {
    "tables": [
      {
        "name": "users",
        "fields": [
          "id",
          "nama",
          "email",
          "password",
          "role",
          "foto",
          "created_at"
        ]
      },
      {
        "name": "tutors",
        "fields": [
          "id",
          "nama",
          "email",
          "spesialisasi",
          "foto"
        ]
      },
      {
        "name": "kelas",
        "fields": [
          "id",
          "nama_kelas",
          "deskripsi",
          "harga",
          "tutor_id"
        ]
      },
      {
        "name": "materi",
        "fields": [
          "id",
          "kelas_id",
          "judul",
          "file_materi",
          "created_at"
        ]
      },
      {
        "name": "jadwal",
        "fields": [
          "id",
          "kelas_id",
          "tanggal",
          "jam_mulai",
          "jam_selesai"
        ]
      },
      {
        "name": "enrollments",
        "fields": [
          "id",
          "user_id",
          "kelas_id",
          "status"
        ]
      },
      {
        "name": "transactions",
        "fields": [
          "id",
          "invoice",
          "user_id",
          "kelas_id",
          "total",
          "status",
          "payment_method",
          "created_at"
        ]
      },
      {
        "name": "payments",
        "fields": [
          "id",
          "transaction_id",
          "gateway_reference",
          "payment_status",
          "paid_at"
        ]
      }
    ]
  },
  "payment_flow": {
    "step_1": "User memilih paket bimbel",
    "step_2": "Sistem membuat invoice",
    "step_3": "Kirim data ke Midtrans/Xendit",
    "step_4": "User melakukan pembayaran",
    "step_5": "Gateway mengirim callback webhook",
    "step_6": "Status transaksi diperbarui",
    "step_7": "Akses kelas otomatis aktif"
  },
  "folder_structure": {
    "root": {
      "assets": [
        "css",
        "js",
        "images"
      ],
      "config": [
        "database.php"
      ],
      "auth": [
        "login.php",
        "register.php",
        "logout.php"
      ],
      "admin": [
        "dashboard.php",
        "users.php",
        "kelas.php",
        "materi.php",
        "transaksi.php"
      ],
      "user": [
        "dashboard.php",
        "kelas.php",
        "materi.php",
        "pembayaran.php"
      ],
      "payment": [
        "checkout.php",
        "callback.php"
      ]
    }
  },
  "ui_design": {
    "reference": "desain.md",
    "theme": {
      "style": "Modern Education Platform",
      "responsive": true,
      "dark_mode": true,
      "sidebar": true,
      "dashboard_cards": true,
      "chart_statistics": true
    },
    "colors": {
      "primary": "#4F46E5",
      "secondary": "#06B6D4",
      "success": "#22C55E",
      "danger": "#EF4444"
    }
  },
  "security": {
    "features": [
      "Password Hashing",
      "Prepared Statement",
      "CSRF Protection",
      "Session Validation",
      "Role Based Access Control",
      "Input Validation"
    ]
  },
  "development_phases": [
    {
      "phase": 1,
      "name": "Setup Project",
      "duration": "2 Hari"
    },
    {
      "phase": 2,
      "name": "Authentication",
      "duration": "3 Hari"
    },
    {
      "phase": 3,
      "name": "Admin Panel",
      "duration": "5 Hari"
    },
    {
      "phase": 4,
      "name": "User Panel",
      "duration": "4 Hari"
    },
    {
      "phase": 5,
      "name": "Payment Gateway",
      "duration": "3 Hari"
    },
    {
      "phase": 6,
      "name": "Testing dan Deployment",
      "duration": "3 Hari"
    }
  ]
}