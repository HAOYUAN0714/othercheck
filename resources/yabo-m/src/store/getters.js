const getters = {
  sidebar: state => state.app.sidebar,
  device: state => state.app.device,
  token: state => state.user.token,
  avatar: state => state.user.avatar,
  name: state => state.user.name,

  mainStruct: state => state.ball.mainStruct,
  tranLeague: state => state.ball.tranLeague,
  dataGame: state => state.ball.dataGame,
  dataOdds: state => state.ball.dataOdds,
  betList: state => state.ball.betList,
  betSlip: state => state.ball.betSlip,
  tran: state => state.ball.tran,
  showBetRecord: state => state.ball.showBetRecord,
  getBetSlip: state => state.ball.curBetSlip,
  getSlipContent: state => state.ball.slipContent,
  getSlipCombo: state => state.ball.slipCombo,
  curGameCount: state => state.ball.getGameCount,
  showAvoid: state => state.ball.showAvoid,
  getNavHeader: state => state.m6app.navHeader,
  getAllEventCount: state => state.m6app.allEventCount,
  openChampionList: state => state.m6app.openChampionList,
  getFavoriteMatch: state => state.m6app.favoriteMatch,
  getSidebarStatus: state => state.m6app.showSidebar
  // getNavHeader: state => console.log(state.m6app.navHeader, '123456789')
}
export default getters
