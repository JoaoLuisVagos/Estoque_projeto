import React from 'react';
import { render, screen } from '@testing-library/react';
import App from './App';

test('renderiza título principal', () => {
  render(<App />);
  const titulo = screen.getByText('Gestão de Estoque');
  expect(titulo).toBeInTheDocument();
});
