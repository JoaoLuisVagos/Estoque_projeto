import React from 'react';
import { render, screen } from '@testing-library/react';
import BebidaList from './BebidaList';

test('renderiza título do estoque de bebidas', () => {
  render(<BebidaList showToast={() => {}} onEdit={() => {}} setRefresh={() => {}} refresh={false} />);
  expect(screen.getByText(/Estoque de Bebidas/i)).toBeInTheDocument();
});
