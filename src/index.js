import { registerBlockType } from '@wordpress/blocks';
import './style.scss';
import json from './block.json';
import Edit from './edit';


const { name } = json;

registerBlockType(name, {
    icon: {
        src: (
<svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 100 125" x="0px" y="0px">
  <title>15</title>
  <g data-name="Group">
    <path data-name="Compound Path" d="M83.7,20.5a8,8,0,0,0-8-8h-19V10.3a6.8,6.8,0,0,0-13.5,0v2.3h-19a8,8,0,0,0-8,8v32a8,8,0,0,0,8,8h19V89.8a6.8,6.8,0,0,0,13.5,0V60.5h19a8,8,0,0,0,8-8ZM47.2,10.3a2.7,2.7,0,0,1,5.5,0v2.3H47.2Zm5.5,79.5a2.7,2.7,0,0,1-5.5,0V60.5h5.5Zm27-37.2a4,4,0,0,1-4,4H24.2a4,4,0,0,1-4-4v-32a4,4,0,0,1,4-4H75.7a4,4,0,0,1,4,4Z" />
    <path data-name="Path" d="M68.6,34.5H31.4a2,2,0,1,0,0,4H68.6a2,2,0,0,0,0-4Z" />
    <path data-name="Path" d="M31.4,27.5H47.6a2,2,0,0,0,0-4H31.4a2,2,0,0,0,0,4Z" />
    <path data-name="Path" d="M68.6,45.5H31.4a2,2,0,1,0,0,4H68.6a2,2,0,0,0,0-4Z" />
  </g>
</svg>
        ),
    },
    edit: Edit,

});