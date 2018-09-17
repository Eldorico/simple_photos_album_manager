import PageAlbumsList from './PageAlbumsList.vue';
import PageAlbumView from './PageAlbumView.vue';

export const routes = [
  {path: '', component:  PageAlbumsList},
  {path: '/albums', component:  PageAlbumsList},
  {path: '/albums/:categoryId', component:  PageAlbumsList, props: true},
  {path: '/album/:id/:categoryReferer', component:  PageAlbumView, props: true},
];
