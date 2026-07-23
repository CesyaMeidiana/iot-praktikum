<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Dosen\DosenDashboardController;
use App\Http\Controllers\Mahasiswa\MahasiswaDashboardController;
use App\Http\Controllers\RealtimeController;
use App\Http\Controllers\Kajur\KajurDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\DeviceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Dosen\ClassroomController as DosenClassroomController;
use App\Http\Controllers\Mahasiswa\ClassroomController as MahasiswaClassroomController;
use App\Http\Controllers\Mahasiswa\ProfileController as MahasiswaProfileController;
use App\Http\Controllers\Dosen\GroupController as DosenGroupController;
use App\Http\Controllers\Mahasiswa\PraktikumController;
use App\Http\Controllers\Dosen\AssignmentController as DosenAssignmentController;
use App\Http\Controllers\Mahasiswa\AssignmentController as MahasiswaAssignmentController;
use App\Http\Controllers\Dosen\DeviceController as DosenDeviceController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Http\Controllers\Dosen\RiwayatController as DosenRiwayatController;
use App\Http\Controllers\Kajur\RiwayatController as KajurRiwayatController;
use App\Http\Controllers\Kajur\ClassroomController as KajurClassroomController;


Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();

        if ($user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->hasRole('Dosen')) {
            return redirect()->route('dosen.dashboard');
        }
        if ($user->hasRole('Mahasiswa')) {
            return redirect()->route('mahasiswa.dashboard');
        }
        if ($user->hasRole('Kajur')) {
            return redirect()->route('kajur.dashboard');
        }
    }

    return redirect()->route('login');
});

Route::get('/captcha-refresh', function () {
    return response()->json(['captcha' => captcha_src('flat')]);
})->name('captcha.refresh');

Route::middleware(['auth'])->group(function () {

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::resource('admin/users', UserController::class);
    Route::resource('admin/classrooms', ClassroomController::class);
    Route::resource('admin/devices', DeviceController::class);

    Route::get('/dosen/dashboard', [DosenDashboardController::class, 'index'])
        ->name('dosen.dashboard');

    Route::get('/mahasiswa/dashboard', [MahasiswaDashboardController::class, 'index'])
        ->name('mahasiswa.dashboard');

    Route::get('/kajur/dashboard', [KajurDashboardController::class, 'index'])
        ->name('kajur.dashboard');

    Route::resource('dosen/classrooms', DosenClassroomController::class)->names('dosen.classrooms');

    Route::get('/mahasiswa/classroom', [MahasiswaClassroomController::class,'index'])->name('mahasiswa.classroom');
    Route::post( '/mahasiswa/classroom/join', [MahasiswaClassroomController::class,'join'])->name('mahasiswa.classroom.join');

    Route::get('/mahasiswa/profile', [MahasiswaProfileController::class,'index'])->name('mahasiswa.profile');
    Route::post('/mahasiswa/profile/join-class',[MahasiswaProfileController::class,'joinClass'])->name('mahasiswa.join-class');

    Route::get('/dosen/classrooms/{classroom}/groups/create',[DosenGroupController::class, 'create'])->name('dosen.groups.create');
    Route::post('/dosen/classrooms/{classroom}/groups',[DosenGroupController::class, 'store'])->name('dosen.groups.store');

    Route::get('/dosen/groups/{group}', [DosenGroupController::class, 'show'])->name('dosen.groups.show');

    Route::resource('mahasiswa/praktikum',PraktikumController::class)->names('mahasiswa.praktikum');
    Route::patch('/mahasiswa/praktikum/{id}/finish',[PraktikumController::class,'finish'])->name('mahasiswa.praktikum.finish');

    Route::get('/mahasiswa/praktikum-active', [PraktikumController::class,'active'])->name('mahasiswa.praktikum.active');

    Route::get('/mahasiswa/praktikum/{id}/download',[PraktikumController::class,'download'])->name('mahasiswa.praktikum.download');
    Route::get('/mahasiswa/praktikum/{id}/pdf',[PraktikumController::class,'downloadPdf'])->name('mahasiswa.praktikum.pdf');


    Route::resource('dosen/tugas',DosenAssignmentController::class)->names('dosen.tugas');
    Route::get('/dosen/classrooms/{classroom}/groups',[DosenAssignmentController::class,'groups'])->name('dosen.tugas.groups');

    Route::get('/mahasiswa/tugas',[MahasiswaAssignmentController::class,'index'])->name('mahasiswa.tugas.index');
    Route::get('/mahasiswa/tugas/{assignment}',[MahasiswaAssignmentController::class,'show'])->name('mahasiswa.tugas.show');
    Route::post('/mahasiswa/tugas/{assignment}/submit', [MahasiswaAssignmentController::class,'submit'])->name('mahasiswa.tugas.submit');

    Route::get('/mahasiswa/notifications', [\App\Http\Controllers\Mahasiswa\NotificationController::class, 'index'])
        ->name('mahasiswa.notifications.index');
    Route::get('/mahasiswa/notifications/all', [\App\Http\Controllers\Mahasiswa\NotificationController::class, 'page'])
        ->name('mahasiswa.notifications.page');
    Route::get('/mahasiswa/notifications/{id}/open', [\App\Http\Controllers\Mahasiswa\NotificationController::class, 'show'])
        ->name('mahasiswa.notifications.open');
    Route::post('/mahasiswa/notifications/{id}/read', [\App\Http\Controllers\Mahasiswa\NotificationController::class, 'markAsRead'])
        ->name('mahasiswa.notifications.read');
    Route::post('/mahasiswa/notifications/mark-all-read', [\App\Http\Controllers\Mahasiswa\NotificationController::class, 'markAllAsRead'])
        ->name('mahasiswa.notifications.markAllRead');

    Route::get('/admin/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])
        ->name('admin.notifications.index');
    Route::get('/admin/notifications/all', [\App\Http\Controllers\Admin\NotificationController::class, 'page'])
        ->name('admin.notifications.page');
    Route::get('/admin/notifications/{id}/open', [\App\Http\Controllers\Admin\NotificationController::class, 'show'])
        ->name('admin.notifications.open');
    Route::post('/admin/notifications/mark-all-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])
        ->name('admin.notifications.markAllRead');

    Route::get('/dosen/notifications', [\App\Http\Controllers\Dosen\NotificationController::class, 'index'])
        ->name('dosen.notifications.index');
    Route::get('/dosen/notifications/all', [\App\Http\Controllers\Dosen\NotificationController::class, 'page'])
        ->name('dosen.notifications.page');
    Route::get('/dosen/notifications/{id}/open', [\App\Http\Controllers\Dosen\NotificationController::class, 'show'])
        ->name('dosen.notifications.open');
    Route::post('/dosen/notifications/mark-all-read', [\App\Http\Controllers\Dosen\NotificationController::class, 'markAllAsRead'])
        ->name('dosen.notifications.markAllRead');

    Route::resource( 'dosen/devices', DosenDeviceController::class)->names('dosen.devices');
    
    Route::get('riwayat/luar', [RiwayatController::class, 'showLuarPraktikum'])->name('admin.riwayat.luar');

    Route::resource('admin/riwayat', RiwayatController::class)->names('admin.riwayat')->only(['index', 'show']);
    Route::resource('dosen/riwayat', DosenRiwayatController::class)->names('dosen.riwayat')->only(['index', 'show']);
    Route::resource('kajur/riwayat', KajurRiwayatController::class)->names('kajur.riwayat')->only(['index', 'show']);

    Route::resource('kajur/users', \App\Http\Controllers\Kajur\UserController::class)->only(['index','show'])->names('kajur.users');
    Route::resource('kajur/classrooms', KajurClassroomController::class)->only(['index', 'show'])->names('kajur.classrooms');

    Route::delete('/praktikum/{id}/hapus-baris', [\App\Http\Controllers\Mahasiswa\PraktikumController::class, 'destroyRow'])->name('mahasiswa.praktikum.destroyRow');
    

    Route::middleware('auth')->get('/tes-auth', function () {
    return response()->json([
        'auth' => auth()->check(),
        'id' => auth()->id(),
        'user' => auth()->user()?->name,
        'session_id' => session()->getId(),
    ]);
});

    Route::get('/realtime', [RealtimeController::class, 'index'])
    ->middleware('auth')
    ->name('realtime');

    Route::get('/admin/riwayat/realtime',
    [RiwayatController::class,'realtime']
)->name('admin.riwayat.realtime');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

require __DIR__.'/auth.php';