# Video Modal Setup Guide

## How to Use the Video Modal

The video modal has been successfully integrated into your landing page! Here's how to customize it:

### 1. Update Your YouTube Video

In `src/components/landing/VideoWidget.vue`, update these values:

```javascript
// Replace with your actual YouTube video ID
const videoId = ref('YOUR_YOUTUBE_VIDEO_ID');

// Update video details
const videoTitle = ref('YOUR_VIDEO_TITLE');
const videoDescription = ref('Your video description');
const videoDuration = ref('MM:SS');

// Update thumbnail (YouTube automatically generates this)
const thumbnailUrl = ref('https://img.youtube.com/vi/YOUR_VIDEO_ID/maxresdefault.jpg');
```

### 2. How to Get YouTube Video ID

1. Go to your YouTube video
2. Copy the URL (e.g., `https://www.youtube.com/watch?v=dQw4w9WgXcQ`)
3. The video ID is the part after `v=` (e.g., `dQw4w9WgXcQ`)

### 3. Features Included

✅ **Modal Overlay**: Click anywhere outside to close
✅ **YouTube Embed**: Full YouTube player with controls
✅ **Responsive Design**: Works on all devices
✅ **Auto-play**: Video starts playing when modal opens
✅ **Thumbnail Preview**: Shows video thumbnail before clicking
✅ **Smooth Animations**: Fade in/out effects
✅ **Close Button**: X button in top-right corner
✅ **Body Scroll Lock**: Prevents background scrolling when modal is open

### 4. Current Video Section Location

The video section appears between "Activities" and "Services" on your landing page.

### 5. Customization Options

- **Position**: Move `<VideoWidget />` to any position in `Landing.vue`
- **Styling**: Modify colors, sizes, and effects in the component files
- **Multiple Videos**: You can create multiple video widgets with different videos

### 6. Testing

1. Visit your landing page
2. Scroll to the "Our Video" section
3. Click the play button or video thumbnail
4. The modal should open with your YouTube video
5. Click outside the modal or the X button to close

The video modal is now ready to use! 🎉
