import PageAlbumsList from './PageAlbumsList.vue';
import PageAlbumView from './PageAlbumView.vue';

export const routes = [
  {path: '', component:  PageAlbumsList},
  {path: '/album', component:  PageAlbumView},
];
