import './bootstrap';

console.log("App.js loaded…");
console.log("AuthUserId:", window.AuthUserId);

if (window.AuthUserId) {
  const channelName = `App.Models.User.${window.AuthUserId}`;
  console.log("✅ Listening on private channel:", channelName);

  window.Echo.private(channelName)
    .notification((notification) => {
      console.log("🔥 New notification received:", notification);

      // هنا بقى اعمل prepend في الجدول/الدراب داون زي ما كنت عامل
    });

} else {
  console.log("❌ No AuthUserId on window.");
}
