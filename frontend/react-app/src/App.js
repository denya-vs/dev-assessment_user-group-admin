import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import UserGroupManager from './components/UserGroupManager';

function App() {
  return (
      <Router>
        <Routes>
          <Route path="/" element={<UserGroupManager />} />
        </Routes>
      </Router>
  );
}

export default App;
