import React from 'react';
import { createRoot } from '@wordpress/element';
import App from './App';
import './index.scss';

console.log( 'Library Manager Script Loaded' );

const container = document.getElementById('library-manager-dashboard');
console.log(container);

if (container) {
    const root = createRoot(container);
    root.render(<App />);
}