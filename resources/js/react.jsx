import React from 'react';
import ReactDOM from 'react-dom/client';

const DashboardStats = ({ stats }) => {
  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      {stats.map((stat, index) => (
        <div key={index} className="card p-6 hover:scale-105 transition-transform duration-300">
          <div className="flex items-center justify-between">
            <div>
              <h3 className="text-sm font-medium text-gray-500">{stat.title}</h3>
              <p className="text-3xl font-bold gradient-text mt-2">{stat.value}</p>
            </div>
            <div className={`p-3 rounded-xl ${stat.colorClass}`}>
              <i className={`fa-solid ${stat.icon} text-xl ${stat.textClass}`}></i>
            </div>
          </div>
        </div>
      ))}
    </div>
  );
};

const AttendanceChart = () => {
  return (
    <div className="card p-6">
      <h3 className="text-lg font-semibold text-gray-900 mb-4">Statistik Absensi Mingguan</h3>
      <div className="flex items-end justify-between h-48 gap-2">
        {['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'].map((day, index) => {
          const height = Math.floor(Math.random() * 60) + 40;
          return (
            <div key={day} className="flex-1 flex flex-col items-center">
              <div 
                className="w-full bg-gradient-to-t from-indigo-500 to-purple-500 rounded-t-lg transition-all duration-300 hover:from-indigo-600 hover:to-purple-600"
                style={{ height: `${height}%` }}
              ></div>
              <span className="text-xs text-gray-500 mt-2">{day}</span>
            </div>
          );
        })}
      </div>
    </div>
  );
};

const FileUploadComponent = () => {
  const [dragActive, setDragActive] = React.useState(false);
  const [files, setFiles] = React.useState([]);

  const handleDrag = (e) => {
    e.preventDefault();
    e.stopPropagation();
    if (e.type === 'dragenter' || e.type === 'dragover') {
      setDragActive(true);
    } else if (e.type === 'dragleave') {
      setDragActive(false);
    }
  };

  const handleDrop = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setDragActive(false);
    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
      setFiles([...files, ...Array.from(e.dataTransfer.files)]);
    }
  };

  const handleChange = (e) => {
    e.preventDefault();
    if (e.target.files && e.target.files[0]) {
      setFiles([...files, ...Array.from(e.target.files)]);
    }
  };

  return (
    <div className="card p-6">
      <h3 className="text-lg font-semibold text-gray-900 mb-4">Upload Dokumen</h3>
      <div
        className={`border-2 border-dashed rounded-xl p-8 text-center transition-all duration-300 ${
          dragActive ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300 hover:border-indigo-400'
        }`}
        onDragEnter={handleDrag}
        onDragLeave={handleDrag}
        onDragOver={handleDrag}
        onDrop={handleDrop}
      >
        <i className="fa-solid fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
        <p className="text-gray-600 mb-2">Drag & drop files here or</p>
        <label className="btn-primary cursor-pointer">
          <span>Browse Files</span>
          <input type="file" multiple className="hidden" onChange={handleChange} />
        </label>
        <p className="text-xs text-gray-400 mt-2">Support: Excel, Word, PDF, Images (Max 10MB)</p>
      </div>
      {files.length > 0 && (
        <div className="mt-4 space-y-2">
          {files.map((file, index) => (
            <div key={index} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
              <div className="flex items-center">
                <i className="fa-solid fa-file text-indigo-500 mr-3"></i>
                <span className="text-sm text-gray-700">{file.name}</span>
              </div>
              <span className="text-xs text-gray-500">{(file.size / 1024).toFixed(2)} KB</span>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

const App = () => {
  const [stats] = React.useState([
    { title: 'Total Karyawan', value: '124', icon: 'fa-users', colorClass: 'bg-blue-100', textClass: 'text-blue-600' },
    { title: 'Absensi Hari Ini', value: '118', icon: 'fa-calendar-check', colorClass: 'bg-green-100', textClass: 'text-green-600' },
    { title: 'Izin Pending', value: '5', icon: 'fa-file-medical', colorClass: 'bg-yellow-100', textClass: 'text-yellow-600' },
    { title: 'Tugas Pending', value: '23', icon: 'fa-tasks', colorClass: 'bg-purple-100', textClass: 'text-purple-600' },
  ]);

  return (
    <div className="space-y-6">
      <DashboardStats stats={stats} />
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <AttendanceChart />
        <FileUploadComponent />
      </div>
    </div>
  );
};

if (document.getElementById('react-root')) {
  ReactDOM.createRoot(document.getElementById('react-root')).render(
    <React.StrictMode>
      <App />
    </React.StrictMode>
  );
}
