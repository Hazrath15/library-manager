import React from 'react';
import { createRoot } from '@wordpress/element';
import App from './App';
import './index.scss';

const container = document.getElementById('library-manager-dashboard');

if (container) {
    const root = createRoot(container);
    root.render(<App />);
}