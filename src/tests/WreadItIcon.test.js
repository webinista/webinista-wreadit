/**
 * @jest-environment jsdom
 */

import {
  describe,
  expect,
  test
} from '@jest/globals';

import { render } from '@testing-library/react';
import WreadItIcon from '../components/WreadItIcon';

const attributes = [
  { fill: '#000' },
  { fill: 'green' }
];

describe('WreadItIcon is rendered', () => {
  test('can find ID', () => {

    const icon = render( <WreadItIcon {...attributes[0] }/> );

    expect(icon.getByTestId('wreadit_icon').getAttribute('id'))
    .toBe('webinista_WreadItIcon');

  });
});

describe('WreadItIcon fill color is ...', () => {

  attributes.forEach((attr) => {
    test( attr.fill , () => {
      const icon = render( <WreadItIcon {...attr }/> );
      const elementFill = icon.getByTestId('wreadit_icon_fill').getAttribute('fill');

      expect( elementFill )
      .toBe( attr.fill );
    });
  });

});
